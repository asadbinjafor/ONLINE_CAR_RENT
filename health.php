<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
try {
    db(false)->query('SELECT 1')->fetchColumn();
    echo json_encode(['status' => 'ok', 'database' => 'ok']);
} catch (Throwable $e) {
    error_log('Health check failed: ' . $e->getMessage());
    http_response_code(503);
    echo json_encode(['status' => 'error', 'database' => 'unavailable']);
}
