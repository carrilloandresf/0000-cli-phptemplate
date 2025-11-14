<?php

namespace App\Infrastructure\Database;

class Database
{
    private static ?\PDO $connection = null;

    public static function connect(): \PDO
    {
        if (self::$connection === null) {
            self::$connection = new \PDO(
                'mysql:host=host.docker.internal;port=3306;dbname=demo_db;charset=utf8mb4',
                'root',
                'root',
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        }
        return self::$connection;
    }

    public static function execute(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
