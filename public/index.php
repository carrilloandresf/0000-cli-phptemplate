<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Application\Services\CalculatorService;
use App\Application\Services\TextProcessor;
use App\Application\Services\UserService;
use App\Application\Services\StatsService;
use App\Infrastructure\Http\Response;
use App\Infrastructure\Database\Database;


header('Content-Type: application/json');

// Inicializar DB
try {
    Database::connect();
} catch (Exception $e) {
    Response::json(['error' => 'Database connection failed'], 500);
}

try {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Router simple
    $route = "$method $path";
    
    switch ($route) {
        // ========== ENDPOINTS EXISTENTES ==========
        case 'GET /api/calculate':
            $expression = $_GET['expression'] ?? '';
            $calculator = new CalculatorService();
            $result = $calculator->process($expression);
            Response::json($result->toArray());
            break;

        case 'GET /api/historyAcronyms':
            $processor = new TextProcessor();
            $history = $processor->getAcronymHistory();
            Response::json(['history' => $history, 'count' => count($history)]);
            break;
            
        case 'POST /api/process-text':
            $input = json_decode(file_get_contents('php://input'), true);
            $text = $input['text'] ?? '';
            $processor = new TextProcessor();
            $result = $processor->transform($text);
            Response::json($result->toArray());
            break;

        // ========== CRUD USERS ==========
        case 'POST /api/users':
            $input = json_decode(file_get_contents('php://input'), true);
            $name = $input['name'] ?? '';
            $email = $input['email'] ?? '';
            
            $userService = new UserService();
            $user = $userService->create($name, $email);
            Response::json($user->toArray(), 201);
            break;
            
        case 'GET /api/users':
            $userService = new UserService();
            $users = $userService->getAll();
            Response::json(['users' => $users, 'count' => count($users)]);
            break;
            
        case 'GET /api/users/{id}':
            preg_match('/\/api\/users\/(\d+)/', $path, $matches);
            $id = (int)($matches[1] ?? 0);
            
            $userService = new UserService();
            $user = $userService->getById($id);
            
            if ($user) {
                Response::json($user->toArray());
            } else {
                Response::json(['error' => 'User not found'], 404);
            }
            break;
            
        case 'PUT /api/users/{id}':
            preg_match('/\/api\/users\/(\d+)/', $path, $matches);
            $id = (int)($matches[1] ?? 0);
            $input = json_decode(file_get_contents('php://input'), true);
            $name = $input['name'] ?? '';
            $email = $input['email'] ?? '';
            
            $userService = new UserService();
            $user = $userService->update($id, $name, $email);
            
            if ($user) {
                Response::json($user->toArray());
            } else {
                Response::json(['error' => 'User not found'], 404);
            }
            break;
            
        case 'DELETE /api/users/{id}':
            preg_match('/\/api\/users\/(\d+)/', $path, $matches);
            $id = (int)($matches[1] ?? 0);
            
            $userService = new UserService();
            $deleted = $userService->delete($id);
            
            if ($deleted) {
                Response::json(['message' => 'User deleted']);
            } else {
                Response::json(['error' => 'User not found'], 404);
            }
            break;

        // ========== STORED PROCEDURES ==========
        case 'GET /api/stats/user-count':
            $statsService = new StatsService();
            $result = $statsService->getUserCount();
            Response::json(['stats' => $result]);
            break;
            
        case 'GET /api/stats/user-stats/{id}':
            preg_match('/\/api\/stats\/user-stats\/(\d+)/', $path, $matches);
            $id = (int)($matches[1] ?? 0);
            
            $statsService = new StatsService();
            $result = $statsService->getUserStats($id);
            
            if ($result) {
                Response::json(['user_stats' => $result]);
            } else {
                Response::json(['error' => 'User stats not found'], 404);
            }
            break;

        case 'GET /api/health':
            Response::json([
                'status' => 'OK', 
                'timestamp' => time(),
                'database' => 'Connected'
            ]);
            break;
            
        default:
            Response::json(['error' => 'Endpoint not found'], 404);
    }
    
} catch (InvalidArgumentException $e) {
    Response::json(['error' => $e->getMessage()], 400);
} catch (Exception $e) {
    Response::json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
}
