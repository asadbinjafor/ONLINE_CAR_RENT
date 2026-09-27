<?php
class AdminController
{
    private CarModel $cars;
    private UserModel $users;
    private OrderModel $orders;
    private BlogModel $blogs;

    public function __construct()
    {
        $this->cars = new CarModel();
        $this->users = new UserModel();
        $this->orders = new OrderModel();
        $this->blogs = new BlogModel();
    }

    public function dashboard(): void
    {
        Auth::requireAdmin();
        view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => [
                'cars' => $this->cars->countAll(),
                'members' => $this->users->countMembers(),
                'orders' => $this->orders->countAll(),
                'blogs' => $this->blogs->countAll(),
            ],
        ]);
    }

    public function cars(): void
    {
        Auth::requireAdmin();
        view('admin/cars/index', [
            'title' => 'Manage Cars',
            'cars' => $this->cars->all(),
        ]);
    }

    public function carCreateForm(): void
    {
        Auth::requireAdmin();
        view('admin/cars/form', [
            'title' => 'Add Car',
            'car' => null,
            'errors' => [],
        ]);
    }

    public function carEditForm(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $car = $this->cars->findById($id);
        if (!$car) {
            redirect('/admin/cars');
        }
        view('admin/cars/form', [
            'title' => 'Edit Car',
            'car' => $car,
            'errors' => [],
        ]);
    }

    public function carStore(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $data = $this->validateCarForm(null);
        if ($data['errors']) {
            view('admin/cars/form', ['title' => 'Add Car', 'car' => $data['old'], 'errors' => $data['errors']]);
            return;
        }
        $this->cars->create($data['fields']);
        flash('success', 'Car added successfully.');
        redirect('/admin/cars');
    }

    public function carUpdate(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        $existing = $this->cars->findById($id);
        if (!$existing) {
            redirect('/admin/cars');
        }
        $data = $this->validateCarForm($existing);
        if ($data['errors']) {
            view('admin/cars/form', ['title' => 'Edit Car', 'car' => array_merge($existing, $data['old']), 'errors' => $data['errors']]);
            return;
        }
        $this->cars->update($id, $data['fields']);
        if (!empty($existing['image_path']) && $data['fields']['image_path'] !== $existing['image_path']) {
            Storage::delete('cars', $existing['image_path']);
        }
        flash('success', 'Car updated successfully.');
        redirect('/admin/cars');
    }

    public function carDelete(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        $car = $this->cars->findById($id);
        if (!$car) {
            redirect('/admin/cars');
        }
        if ($this->cars->hasActiveOrders($id)) {
            flash('success', 'Cannot delete car with pending or confirmed orders.');
            redirect('/admin/cars');
        }
        $this->cars->delete($id);
        if (!empty($car['image_path'])) {
            Storage::delete('cars', $car['image_path']);
        }
        flash('success', 'Car deleted.');
        redirect('/admin/cars');
    }

    public function members(): void
    {
        Auth::requireAdmin();
        view('admin/members', [
            'title' => 'Manage Members',
            'members' => $this->users->allMembers(),
        ]);
    }

    public function orders(): void
    {
        Auth::requireAdmin();
        $status = trim($_GET['status'] ?? '');
        $from = trim($_GET['from'] ?? '');
        $to = trim($_GET['to'] ?? '');
        view('admin/orders', [
            'title' => 'Rent Order History',
            'orders' => $this->orders->allFiltered($status ?: null, $from ?: null, $to ?: null),
            'filters' => compact('status', 'from', 'to'),
        ]);
    }

    private function validateCarForm(?array $existing): array
    {
        $name = trim($_POST['name'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $price = (float) ($_POST['price_per_day'] ?? 0);
        $status = $_POST['availability_status'] ?? 'available';
        $description = trim($_POST['description'] ?? '');
        $errors = [];
        $old = [
            'name' => $name,
            'model' => $model,
            'type' => $type,
            'price_per_day' => $price,
            'availability_status' => $status,
            'description' => $description,
        ];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }
        if ($model === '') {
            $errors['model'] = 'Model is required.';
        }
        if (!in_array($type, CAR_TYPES, true)) {
            $errors['type'] = 'Select a valid car type.';
        }
        if ($price <= 0) {
            $errors['price_per_day'] = 'Price must be greater than zero.';
        }
        if (!in_array($status, CAR_STATUSES, true)) {
            $errors['availability_status'] = 'Invalid status.';
        }
        if ($description === '') {
            $errors['description'] = 'Description is required.';
        }

        $imagePath = $existing['image_path'] ?? null;
        if (!$existing && empty($_FILES['image']['name'])) {
            $errors['image'] = 'Car image is required.';
        }
        if (!empty($_FILES['image']['name'])) {
            $uploadErr = Security::validateImageUpload($_FILES['image'], !$existing);
            if ($uploadErr) {
                $errors['image'] = $uploadErr;
            } else {
                $saved = Security::saveUpload($_FILES['image'], CAR_UPLOAD_DIR, 'car');
                if ($saved) {
                    $imagePath = $saved;
                } else {
                    $errors['image'] = 'Could not save image.';
                }
            }
        }

        return [
            'errors' => $errors,
            'old' => $old,
            'fields' => [
                'name' => $name,
                'model' => $model,
                'type' => $type,
                'price_per_day' => $price,
                'availability_status' => $status,
                'description' => $description,
                'image_path' => $imagePath,
            ],
        ];
    }
}
