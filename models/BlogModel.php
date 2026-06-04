<?php
class BlogModel
{
    public function all(): array
    {
        return db()->query(
            'SELECT b.*, u.name AS author_name, u.role AS author_role
             FROM blogs b
             JOIN users u ON u.id = b.user_id
             ORDER BY b.created_at DESC'
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $st = db()->prepare(
            'SELECT b.*, u.name AS author_name, u.role AS author_role
             FROM blogs b JOIN users u ON u.id = b.user_id WHERE b.id = ? LIMIT 1'
        );
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(int $userId, string $title, string $content): int
    {
        $st = db()->prepare('INSERT INTO blogs (user_id, title, content) VALUES (?,?,?)');
        $st->execute([$userId, $title, $content]);
        return (int) db()->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $st = db()->prepare('DELETE FROM blogs WHERE id = ?');
        $st->execute([$id]);
        return $st->rowCount() > 0;
    }

    public function countAll(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM blogs')->fetchColumn();
    }
}
