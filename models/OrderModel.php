<?php
class OrderModel
{
    public function findById(int $id): ?array
    {
        $st = db()->prepare(
            'SELECT o.*, c.name AS car_name, c.model AS car_model, c.type AS car_type, c.price_per_day,
                    c.image_path AS car_image, u.name AS user_name, u.email AS user_email
             FROM orders o
             JOIN cars c ON c.id = o.car_id
             JOIN users u ON u.id = o.user_id
             WHERE o.id = ? LIMIT 1'
        );
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(int $userId, int $carId, string $start, string $end, float $total): int
    {
        $st = db()->prepare(
            'INSERT INTO orders (user_id, car_id, start_date, end_date, total_cost, status) VALUES (?,?,?,?,?,?) RETURNING id'
        );
        $st->execute([$userId, $carId, $start, $end, $total, 'pending']);
        return (int) $st->fetchColumn();
    }

    public function updateStatus(int $id, string $status, ?string $paymentMethod = null): void
    {
        if ($paymentMethod !== null) {
            $st = db()->prepare('UPDATE orders SET status=?, payment_method=? WHERE id=?');
            $st->execute([$status, $paymentMethod, $id]);
        } else {
            $st = db()->prepare('UPDATE orders SET status=? WHERE id=?');
            $st->execute([$status, $id]);
        }
    }

    public function cancel(int $id): void
    {
        $this->updateStatus($id, 'cancelled');
    }

    public function confirm(int $id, string $paymentMethod): void
    {
        $this->updateStatus($id, 'confirmed', $paymentMethod);
    }

    public function forUser(int $userId): array
    {
        $st = db()->prepare(
            'SELECT o.*, c.name AS car_name, c.model AS car_model, c.type AS car_type
             FROM orders o
             JOIN cars c ON c.id = o.car_id
             WHERE o.user_id = ?
             ORDER BY o.order_date DESC'
        );
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public function allFiltered(?string $status = null, ?string $from = null, ?string $to = null): array
    {
        $sql = 'SELECT o.*, c.name AS car_name, c.model AS car_model, c.type AS car_type,
                       u.name AS user_name, u.email AS user_email
                FROM orders o
                JOIN cars c ON c.id = o.car_id
                JOIN users u ON u.id = o.user_id
                WHERE 1=1';
        $params = [];
        if ($status && in_array($status, ORDER_STATUSES, true)) {
            $sql .= ' AND o.status = ?';
            $params[] = $status;
        }
        if ($from) {
            $sql .= ' AND o.start_date >= ?';
            $params[] = $from;
        }
        if ($to) {
            $sql .= ' AND o.end_date <= ?';
            $params[] = $to;
        }
        $sql .= ' ORDER BY o.order_date DESC';
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function countAll(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    }

    public static function calculateDays(string $start, string $end): int
    {
        $s = new DateTime($start);
        $e = new DateTime($end);
        $days = (int) $s->diff($e)->days;
        return max(1, $days);
    }

    public static function calculateTotal(float $pricePerDay, string $start, string $end): float
    {
        return round($pricePerDay * self::calculateDays($start, $end), 2);
    }
}
