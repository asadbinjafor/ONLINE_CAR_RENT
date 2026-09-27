<?php
class PaymentModel
{
    public function create(int $orderId, float $amount, string $method, ?string $transactionId): int
    {
        $st = db()->prepare(
            'INSERT INTO payments (order_id, amount, payment_method, transaction_id) VALUES (?,?,?,?) RETURNING id'
        );
        $st->execute([$orderId, $amount, $method, $transactionId]);
        return (int) $st->fetchColumn();
    }

    public function findByOrderId(int $orderId): ?array
    {
        $st = db()->prepare('SELECT * FROM payments WHERE order_id = ? ORDER BY payment_date DESC LIMIT 1');
        $st->execute([$orderId]);
        $row = $st->fetch();
        return $row ?: null;
    }
}
