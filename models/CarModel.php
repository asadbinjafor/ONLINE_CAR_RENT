<?php
class CarModel
{
    public function findById(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM cars WHERE id = ? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function all(?string $type = null, ?string $search = null): array
    {
        $sql = 'SELECT * FROM cars WHERE 1=1';
        $params = [];
        if ($type) {
            $sql .= ' AND type = ?';
            $params[] = $type;
        }
        if ($search) {
            $sql .= ' AND (name ILIKE ? OR model ILIKE ? OR description ILIKE ?)';
            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        $sql .= ' ORDER BY created_at DESC';
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function distinctTypes(): array
    {
        return db()->query('SELECT DISTINCT type FROM cars ORDER BY type ASC')->fetchAll(PDO::FETCH_COLUMN);
    }

    public function featured(int $limit = 4): array
    {
        $st = db()->prepare(
            "SELECT c.*, COUNT(o.id) AS rent_count
             FROM cars c
             LEFT JOIN orders o ON o.car_id = c.id AND o.status = 'confirmed'
             WHERE c.availability_status = 'available'
             GROUP BY c.id
             ORDER BY rent_count DESC, RANDOM()
             LIMIT ?"
        );
        $st->bindValue(1, $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function countAll(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM cars')->fetchColumn();
    }

    public function create(array $data): int
    {
        $st = db()->prepare(
            'INSERT INTO cars (name, model, type, price_per_day, availability_status, image_path, description)
             VALUES (?,?,?,?,?,?,?) RETURNING id'
        );
        $st->execute([
            $data['name'],
            $data['model'],
            $data['type'],
            $data['price_per_day'],
            $data['availability_status'],
            $data['image_path'] ?? null,
            $data['description'],
        ]);
        return (int) $st->fetchColumn();
    }

    public function update(int $id, array $data): void
    {
        if (!empty($data['image_path'])) {
            $st = db()->prepare(
                'UPDATE cars SET name=?, model=?, type=?, price_per_day=?, availability_status=?, image_path=?, description=? WHERE id=?'
            );
            $st->execute([
                $data['name'],
                $data['model'],
                $data['type'],
                $data['price_per_day'],
                $data['availability_status'],
                $data['image_path'],
                $data['description'],
                $id,
            ]);
        } else {
            $st = db()->prepare(
                'UPDATE cars SET name=?, model=?, type=?, price_per_day=?, availability_status=?, description=? WHERE id=?'
            );
            $st->execute([
                $data['name'],
                $data['model'],
                $data['type'],
                $data['price_per_day'],
                $data['availability_status'],
                $data['description'],
                $id,
            ]);
        }
    }

    public function delete(int $id): bool
    {
        $st = db()->prepare('DELETE FROM cars WHERE id = ?');
        $st->execute([$id]);
        return $st->rowCount() > 0;
    }

    public function hasActiveOrders(int $carId): bool
    {
        $st = db()->prepare("SELECT id FROM orders WHERE car_id = ? AND status IN ('pending','confirmed') LIMIT 1");
        $st->execute([$carId]);
        return (bool) $st->fetch();
    }
}
