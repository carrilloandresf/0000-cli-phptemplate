<?php

namespace App\Application\Services;

use App\Infrastructure\Database\Database;

class StatsService
{
    /**
     * Ejecuta stored procedure SIN parámetros
     * Ejemplo: CALL GetUserCount()
     */
    public function getUserCount(): array
    {
        $stmt = Database::execute("CALL GetUserCount()");
        return $stmt->fetch();
    }

    /**
     * Ejecuta stored procedure CON parámetros  
     * Ejemplo: CALL GetUserStats(1)
     */
    public function getUserStats(int $userId): ?array
    {
        $stmt = Database::execute("CALL GetUserStats(?)", [$userId]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }

    /**
     * Ejemplo adicional: SP que devuelve múltiples resultados
     */
    public function getUserSummary(int $userId): array
    {
        $stmt = Database::execute("CALL GetUserStats(?)", [$userId]);
        $userStats = $stmt->fetch();
        
        $stmt->nextRowset(); // Si hubiera múltiples resultsets
        
        $countStmt = Database::execute("CALL GetUserCount()");
        $totalUsers = $countStmt->fetch();
        
        return [
            'user_stats' => $userStats,
            'system_stats' => $totalUsers
        ];
    }
}
