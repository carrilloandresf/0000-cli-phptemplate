<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Application\Services\CalculatorService;
use App\Application\Services\TextProcessor;
use App\Application\Services\UserService;
use App\Application\Services\StatsService;
use App\Infrastructure\Http\Response;
use App\Infrastructure\Database\Database;

// Inicializar DB
try {
    Database::connect();
} catch (Exception $e) {
    Response::json(['error' => 'Database connection failed'], 500);
}

try {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET' && $path === '/') {
        header('Content-Type: text/html; charset=utf-8');
        readfile(__DIR__ . '/frontend.html');
        exit;
    }

    // ========== ENDPOINTS EXISTENTES ==========
    if ($method === 'GET' && $path === '/api/calculate') {
        $expression = $_GET['expression'] ?? '';
        $calculator = new CalculatorService();
        $result = $calculator->process($expression);
        Response::json($result->toArray());
    }

    if ($method === 'POST' && $path === '/api/process-text') {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $text = $input['text'] ?? '';
        $processor = new TextProcessor();
        $result = $processor->transform($text);
        Response::json($result->toArray());
    }

    // ========== CRUD USERS ==========
    if ($method === 'POST' && $path === '/api/users') {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';

        $userService = new UserService();
        $user = $userService->create($name, $email);
        Response::json($user->toArray(), 201);
    }

    if ($method === 'GET' && $path === '/api/users') {
        $userService = new UserService();
        $users = $userService->getAll();
        Response::json(['users' => $users, 'count' => count($users)]);
    }

    if ($method === 'GET' && preg_match('/^\/api\/users\/(\d+)$/', $path, $matches)) {
        $id = (int)($matches[1] ?? 0);

        $userService = new UserService();
        $user = $userService->getById($id);

        if ($user) {
            Response::json($user->toArray());
        } else {
            Response::json(['error' => 'User not found'], 404);
        }
    }

    if ($method === 'PUT' && preg_match('/^\/api\/users\/(\d+)$/', $path, $matches)) {
        $id = (int)($matches[1] ?? 0);
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';

        $userService = new UserService();
        $user = $userService->update($id, $name, $email);

        if ($user) {
            Response::json($user->toArray());
        } else {
            Response::json(['error' => 'User not found'], 404);
        }
    }

    if ($method === 'DELETE' && preg_match('/^\/api\/users\/(\d+)$/', $path, $matches)) {
        $id = (int)($matches[1] ?? 0);

        $userService = new UserService();
        $deleted = $userService->delete($id);

        if ($deleted) {
            Response::json(['message' => 'User deleted']);
        } else {
            Response::json(['error' => 'User not found'], 404);
        }
    }

    // ========== STORED PROCEDURES ==========
    if ($method === 'GET' && $path === '/api/stats/user-count') {
        $statsService = new StatsService();
        $result = $statsService->getUserCount();
        Response::json(['stats' => $result]);
    }

    if ($method === 'GET' && preg_match('/^\/api\/stats\/user-stats\/(\d+)$/', $path, $matches)) {
        $id = (int)($matches[1] ?? 0);

        $statsService = new StatsService();
        $result = $statsService->getUserStats($id);

        if ($result) {
            Response::json(['user_stats' => $result]);
        } else {
            Response::json(['error' => 'User stats not found'], 404);
        }
    }

    if ($method === 'GET' && $path === '/api/health') {
        Response::json([
            'status' => 'OK',
            'timestamp' => time(),
            'database' => 'Connected'
        ]);
    }

    Response::json(['error' => 'Endpoint not found'], 404);
} catch (InvalidArgumentException $e) {
    Response::json(['error' => $e->getMessage()], 400);
} catch (Exception $e) {
    Response::json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
}
