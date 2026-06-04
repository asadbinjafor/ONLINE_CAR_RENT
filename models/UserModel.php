<?php
class UserModel
{
    public function findByEmail(string $email): ?array
    {
        $st = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM users WHERE email = ?';
        $params = [$email];
        if ($excludeId) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $st = db()->prepare($sql . ' LIMIT 1');
        $st->execute($params);
        return (bool) $st->fetch();
    }

    public function create(string $name, string $email, string $passwordHash, string $role, string $address, string $phone): int
    {
        $st = db()->prepare('INSERT INTO users (name, email, password_hash, role, address, phone) VALUES (?,?,?,?,?,?)');
        $st->execute([$name, $email, $passwordHash, $role, $address, $phone]);
        return (int) db()->lastInsertId();
    }

    public function updateProfile(int $id, string $name, string $email, string $address, string $phone, ?string $picture): void
    {
        if ($picture) {
            $st = db()->prepare('UPDATE users SET name=?, email=?, address=?, phone=?, profile_picture=? WHERE id=?');
            $st->execute([$name, $email, $address, $phone, $picture, $id]);
        } else {
            $st = db()->prepare('UPDATE users SET name=?, email=?, address=?, phone=? WHERE id=?');
            $st->execute([$name, $email, $address, $phone, $id]);
        }
    }

    public function updatePassword(int $id, string $hash): void
    {
        $st = db()->prepare('UPDATE users SET password_hash=? WHERE id=?');
        $st->execute([$hash, $id]);
    }

    public function setRememberToken(int $id, string $plainToken): void
    {
        $hash = hash_hmac('sha256', $plainToken, REMEMBER_SECRET);
        $st = db()->prepare('UPDATE users SET remember_token=? WHERE id=?');
        $st->execute([$hash, $id]);
    }

    public function clearRememberToken(int $id): void
    {
        $st = db()->prepare('UPDATE users SET remember_token=NULL WHERE id=?');
        $st->execute([$id]);
    }

    public function findByRememberToken(int $id, string $plainToken): ?array
    {
        $hash = hash_hmac('sha256', $plainToken, REMEMBER_SECRET);
        $st = db()->prepare('SELECT id, name, email, role FROM users WHERE id=? AND remember_token=? LIMIT 1');
        $st->execute([$id, $hash]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function allMembers(): array
    {
        return db()->query("SELECT id, name, email, address, phone, created_at FROM users WHERE role='member' ORDER BY created_at DESC")->fetchAll();
    }

    public function countMembers(): int
    {
        return (int) db()->query("SELECT COUNT(*) FROM users WHERE role='member'")->fetchColumn();
    }

    public function deleteMember(int $id): bool
    {
        $st = db()->prepare("DELETE FROM users WHERE id=? AND role='member'");
        $st->execute([$id]);
        return $st->rowCount() > 0;
    }
}
