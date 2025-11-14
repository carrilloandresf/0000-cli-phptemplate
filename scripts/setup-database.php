<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Database;

try {
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: 'root';

    $dsn = sprintf('mysql:host=%s;port=%s', $host, $port);

    $pdo = new PDO($dsn, $username, $password);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS demo_db");
    $pdo->exec("USE demo_db");
    
    // Crear tabla users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    echo "✅ Database and table created successfully\n";
    
    // Insertar datos de prueba
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (name, email) VALUES (?, ?)");
    $users = [
        ['John Doe', 'john@example.com'],
        ['Jane Smith', 'jane@example.com'],
        ['Bob Wilson', 'bob@example.com']
    ];
    
    foreach ($users as $user) {
        $stmt->execute($user);
    }
    
    echo "✅ Sample data inserted\n";
    
    // ==================== STORED PROCEDURES ====================
    
    // SP sin parámetros - Conteo total de usuarios
    $pdo->exec("DROP PROCEDURE IF EXISTS GetUserCount");
    $pdo->exec("
        CREATE PROCEDURE GetUserCount()
        BEGIN
            SELECT COUNT(*) as total_users FROM users;
        END
    ");
    echo "✅ Stored Procedure 'GetUserCount' created\n";
    
    // SP con parámetros - Stats específicos de usuario
    $pdo->exec("Drop PROCEDURE IF EXISTS GetUserStats");
    $pdo->exec("
        CREATE PROCEDURE GetUserStats(IN user_id INT)
        BEGIN
            SELECT 
                u.id,
                u.name,
                u.email,
                u.created_at,
                LENGTH(u.name) as name_length,
                LOCATE('@', u.email) as at_position
            FROM users u 
            WHERE u.id = user_id;
        END
    ");
    echo "✅ Stored Procedure 'GetUserStats' created\n";
    
    echo "🎉 Database setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}