<?php
class CarController
{
    private CarModel $cars;

    public function __construct()
    {
        $this->cars = new CarModel();
    }

    public function index(): void
    {
        $type = trim($_GET['type'] ?? '');
        $search = trim($_GET['q'] ?? '');
        view('cars/index', [
            'title' => $type ? $type . ' Cars' : 'Browse Cars',
            'cars' => $this->cars->all($type ?: null, $search ?: null),
            'categories' => $this->cars->distinctTypes(),
            'activeCategory' => $type,
            'search' => $search,
        ]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $car = $this->cars->findById($id);
        if (!$car) {
            http_response_code(404);
            view('errors/404', ['title' => 'Car Not Found']);
            return;
        }
        view('cars/show', [
            'title' => $car['name'],
            'car' => $car,
        ]);
    }
}
