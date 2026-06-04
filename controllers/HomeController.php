<?php
class HomeController
{
    private CarModel $cars;

    public function __construct()
    {
        $this->cars = new CarModel();
    }

    public function index(): void
    {
        view('home/index', [
            'title' => APP_NAME,
            'featuredCars' => $this->cars->featured(4),
            'categories' => $this->cars->distinctTypes(),
            'activeCategory' => null,
        ]);
    }
}
