<?php

namespace App\Application\Services;

use App\Domain\Models\User;
use App\Infrastructure\Database\Database;

class UserService
{
    public function create(string $name, string $email): User
    {
        if (empty($name) || empty($email)) {
            throw new \InvalidArgumentException("Name and email are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }

        $sql = "INSERT INTO users (name, email, created_at) VALUES (?, ?, NOW())";
        Database::execute($sql, [$name, $email]);
        
        $userId = Database::connect()->lastInsertId();
        
        return new User($userId, $name, $email, date('Y-m-d H:i:s'));
    }

    public function getAll(): array
    {
        $sql = "SELECT id, name, email, created_at FROM users ORDER BY id DESC";
        $stmt = Database::execute($sql);
        
        $users = [];
        while ($row = $stmt->fetch()) {
            $users[] = User::fromArray($row)->toArray();
        }
        
        return $users;
    }

    public function getById(int $id): ?User
    {
        $sql = "SELECT id, name, email, created_at FROM users WHERE id = ?";
        $stmt = Database::execute($sql, [$id]);
        $data = $stmt->fetch();

        return $data ? User::fromArray($data) : null;
    }

    public function update(int $id, string $name, string $email): ?User
    {
        $existing = $this->getById($id);
        if (!$existing) {
            return null;
        }

        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        Database::execute($sql, [$name, $email, $id]);

        return new User($id, $name, $email, $existing->createdAt);
    }

    public function delete(int $id): bool
    {
        $existing = $this->getById($id);
        if (!$existing) {
            return false;
        }

        $sql = "DELETE FROM users WHERE id = ?";
        Database::execute($sql, [$id]);

        return true;
    }
}
