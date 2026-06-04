<?php
class OrderController
{
    private CarModel $cars;
    private OrderModel $orders;
    private PaymentModel $payments;

    public function __construct()
    {
        $this->cars = new CarModel();
        $this->orders = new OrderModel();
        $this->payments = new PaymentModel();
    }

    public function createForm(): void
    {
        Auth::requireMember();
        $carId = (int) ($_GET['car_id'] ?? 0);
        $car = $this->cars->findById($carId);
        if (!$car || $car['availability_status'] !== 'available') {
            flash('success', 'This car is not available for rent.');
            redirect('/cars');
        }
        view('orders/create', [
            'title' => 'Rent ' . $car['name'],
            'car' => $car,
            'errors' => [],
        ]);
    }

    public function create(): void
    {
        Auth::requireMember();
        Security::requireCsrfPost();
        $carId = (int) ($_POST['car_id'] ?? 0);
        $start = trim($_POST['start_date'] ?? '');
        $end = trim($_POST['end_date'] ?? '');
        $car = $this->cars->findById($carId);
        $errors = [];

        if (!$car || $car['availability_status'] !== 'available') {
            flash('success', 'Car not available.');
            redirect('/cars');
        }
        $today = date('Y-m-d');
        if ($start === '' || $end === '') {
            $errors['dates'] = 'Both start and end dates are required.';
        } elseif ($start < $today) {
            $errors['start_date'] = 'Start date cannot be in the past.';
        } elseif ($end < $start) {
            $errors['end_date'] = 'End date must be after start date.';
        }

        if ($errors) {
            view('orders/create', ['title' => 'Rent ' . $car['name'], 'car' => $car, 'errors' => $errors]);
            return;
        }

        $total = OrderModel::calculateTotal((float) $car['price_per_day'], $start, $end);
        $orderId = $this->orders->create(Auth::user()['id'], $carId, $start, $end, $total);
        redirect('/order/invoice', ['id' => $orderId]);
    }

    public function invoice(): void
    {
        Auth::requireMember();
        $order = $this->getOwnOrder((int) ($_GET['id'] ?? 0));
        if (!$order || $order['status'] !== 'pending') {
            redirect('/');
        }
        view('orders/invoice', ['title' => 'Invoice', 'order' => $order]);
    }

    public function cancel(): void
    {
        Auth::requireMember();
        Security::requireCsrfPost();
        $order = $this->getOwnOrder((int) ($_POST['order_id'] ?? 0));
        if ($order && $order['status'] === 'pending') {
            $this->orders->cancel((int) $order['id']);
            flash('success', 'Order cancelled.');
        }
        redirect('/');
    }

    public function finalize(): void
    {
        Auth::requireMember();
        Security::requireCsrfPost();
        $order = $this->getOwnOrder((int) ($_POST['order_id'] ?? 0));
        if (!$order || $order['status'] !== 'pending') {
            redirect('/');
        }
        redirect('/order/payment', ['id' => $order['id']]);
    }

    public function paymentForm(): void
    {
        Auth::requireMember();
        $order = $this->getOwnOrder((int) ($_GET['id'] ?? 0));
        if (!$order || $order['status'] !== 'pending') {
            redirect('/');
        }
        view('orders/payment', [
            'title' => 'Payment',
            'order' => $order,
            'errors' => [],
        ]);
    }

    public function processPayment(): void
    {
        Auth::requireMember();
        Security::requireCsrfPost();
        $order = $this->getOwnOrder((int) ($_POST['order_id'] ?? 0));
        if (!$order || $order['status'] !== 'pending') {
            redirect('/');
        }
        $method = $_POST['payment_method'] ?? '';
        $transactionId = trim($_POST['transaction_id'] ?? '');
        $errors = [];

        if (!array_key_exists($method, PAYMENT_METHODS)) {
            $errors['payment_method'] = 'Select a valid payment method.';
        }
        if (in_array($method, ['bkash', 'nagad', 'bank_transfer', 'credit_card'], true) && $transactionId === '') {
            $errors['transaction_id'] = 'Transaction/reference ID is required for this method.';
        }

        if ($errors) {
            view('orders/payment', ['title' => 'Payment', 'order' => $order, 'errors' => $errors]);
            return;
        }

        if ($transactionId === '') {
            $transactionId = 'COD-' . $order['id'] . '-' . time();
        }

        $this->orders->confirm((int) $order['id'], $method);
        $this->payments->create((int) $order['id'], (float) $order['total_cost'], $method, $transactionId);
        redirect('/order/success', ['id' => $order['id']]);
    }

    public function success(): void
    {
        Auth::requireMember();
        $order = $this->getOwnOrder((int) ($_GET['id'] ?? 0));
        if (!$order || $order['status'] !== 'confirmed') {
            redirect('/');
        }
        $payment = $this->payments->findByOrderId((int) $order['id']);
        view('orders/success', [
            'title' => 'Booking Confirmed',
            'order' => $order,
            'payment' => $payment,
        ]);
    }

    private function getOwnOrder(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        $order = $this->orders->findById($id);
        if (!$order || (int) $order['user_id'] !== Auth::user()['id']) {
            return null;
        }
        return $order;
    }
}
