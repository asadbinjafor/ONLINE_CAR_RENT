<?php
class ApiController
{
    private CarModel $cars;
    private OrderModel $orders;
    private BlogModel $blogs;

    public function __construct()
    {
        $this->cars = new CarModel();
        $this->orders = new OrderModel();
        $this->blogs = new BlogModel();
    }

    public function carSearch(): void
    {
        $q = trim($_GET['q'] ?? '');
        $type = trim($_GET['type'] ?? '');
        $cars = $this->cars->all($type ?: null, $q ?: null);
        $items = array_map(function (array $car): array {
            return [
                'id' => (int) $car['id'],
                'name' => $car['name'],
                'model' => $car['model'],
                'type' => $car['type'],
                'price_per_day' => (float) $car['price_per_day'],
                'availability_status' => $car['availability_status'],
                'image_url' => $car['image_path'] ? Storage::url('cars', $car['image_path']) : null,
                'url' => app_link('/car', ['id' => (int) $car['id']]),
            ];
        }, $cars);
        Security::json(['success' => true, 'cars' => $items]);
    }

    public function calculateCost(): void
    {
        $carId = (int) ($_GET['car_id'] ?? 0);
        $start = trim($_GET['start_date'] ?? '');
        $end = trim($_GET['end_date'] ?? '');
        $car = $this->cars->findById($carId);
        if (!$car || $start === '' || $end === '') {
            Security::json(['success' => false, 'message' => 'Invalid input.'], 400);
        }
        $today = date('Y-m-d');
        if ($start < $today || $end < $start) {
            Security::json(['success' => false, 'message' => 'Invalid date range.'], 400);
        }
        $days = OrderModel::calculateDays($start, $end);
        $total = OrderModel::calculateTotal((float) $car['price_per_day'], $start, $end);
        Security::json([
            'success' => true,
            'days' => $days,
            'price_per_day' => (float) $car['price_per_day'],
            'total_cost' => $total,
        ]);
    }

    public function orderCancelAjax(): void
    {
        Auth::requireMember();
        $id = (int) ($_GET['id'] ?? 0);
        $order = $this->orders->findById($id);
        if (!$order || (int) $order['user_id'] !== Auth::user()['id'] || $order['status'] !== 'pending') {
            Security::json(['success' => false, 'message' => 'Order not found or cannot be cancelled.'], 403);
        }
        $this->orders->cancel($id);
        Security::json(['success' => true, 'message' => 'Order cancelled.']);
    }

    public function blogCreate(): void
    {
        Auth::requireRole('admin', 'member');
        $input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: $_POST;
        if (!Security::verifyCsrf($input['csrf_token'] ?? null)) {
            Security::json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $title = trim($input['title'] ?? '');
        $content = trim($input['content'] ?? '');
        if ($title === '') {
            Security::json(['success' => false, 'message' => 'Title is required.'], 422);
        }
        if ($content === '') {
            Security::json(['success' => false, 'message' => 'Content is required.'], 422);
        }
        $id = $this->blogs->create(Auth::user()['id'], $title, $content);
        $post = $this->blogs->findById($id);
        Security::json([
            'success' => true,
            'post' => [
                'id' => (int) $post['id'],
                'title' => $post['title'],
                'content' => $post['content'],
                'author_name' => $post['author_name'],
                'created_at' => $post['created_at'],
                'user_id' => (int) $post['user_id'],
            ],
        ]);
    }

    public function blogDelete(): void
    {
        Auth::requireLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $post = $this->blogs->findById($id);
        if (!$post) {
            Security::json(['success' => false, 'message' => 'Post not found.'], 404);
        }
        $user = Auth::user();
        $canDelete = $user['role'] === 'admin' || (int) $post['user_id'] === $user['id'];
        if (!$canDelete) {
            Security::json(['success' => false, 'message' => 'Not authorized.'], 403);
        }
        $this->blogs->delete($id);
        Security::json(['success' => true, 'message' => 'Post deleted.']);
    }
}
