<?php
function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
    $port = defined('DB_PORT') ? DB_PORT : '3306';
    $name = defined('DB_NAME') ? DB_NAME : 'project5';
    $user = defined('DB_USER') ? DB_USER : 'root';
    $pass = defined('DB_PASS') ? DB_PASS : '';

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        $isRefused = str_contains($e->getMessage(), '2002') || str_contains($e->getMessage(), 'actively refused');
        if ($isRefused) {
            dbConnectionError(
                'MySQL is not running',
                '<p><strong>Fix:</strong> Open <strong>XAMPP Control Panel</strong> → start <strong>MySQL</strong>, then refresh.</p>'
            );
        }
        if (str_contains($e->getMessage(), 'Unknown database')) {
            dbConnectionError(
                'Database not found',
                '<p><strong>Fix:</strong> Import <code>database.sql</code> in phpMyAdmin (creates database <code>project5</code>).</p>'
            );
        }
        if (str_contains($e->getMessage(), 'could not find driver')) {
            dbConnectionError(
                'PHP MySQL driver missing',
                '<p>Enable <code>extension=pdo_mysql</code> in php.ini and restart Apache.</p>'
            );
        }
        dbConnectionError('Database connection failed', '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>');
    }

    return $pdo;
}

function dbConnectionError(string $title, string $body): void
{
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>'
        . htmlspecialchars($title, ENT_QUOTES) . '</title>'
        . '<style>body{font-family:system-ui,sans-serif;max-width:520px;margin:48px auto;padding:24px;background:#0f172a;color:#e2e8f0}'
        . 'h1{color:#f97316}</style></head><body><h1>'
        . htmlspecialchars($title, ENT_QUOTES) . '</h1>' . $body . '</body></html>';
    exit;
}
