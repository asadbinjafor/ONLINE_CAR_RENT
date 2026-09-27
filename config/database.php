<?php
function db(bool $renderError = true): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $url = getenv('DATABASE_URL');
    $parts = $url ? parse_url($url) : [];
    if ($parts === false) {
        throw new RuntimeException('Invalid DATABASE_URL');
    }
    $query = [];
    parse_str($parts['query'] ?? '', $query);
    $host = $parts['host'] ?? (getenv('DB_HOST') ?: '127.0.0.1');
    $port = $parts['port'] ?? (getenv('DB_PORT') ?: '5432');
    $name = isset($parts['path']) ? ltrim($parts['path'], '/') : (getenv('DB_NAME') ?: 'project5');
    $user = isset($parts['user']) ? rawurldecode($parts['user']) : (getenv('DB_USER') ?: 'postgres');
    $pass = isset($parts['pass']) ? rawurldecode($parts['pass']) : (getenv('DB_PASSWORD') ?: '');
    $sslmode = $query['sslmode'] ?? (getenv('DB_SSLMODE') ?: 'prefer');
    $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode={$sslmode}";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        if (!$renderError) {
            throw $e;
        }
        dbConnectionError('Database connection failed', '<p>Check PostgreSQL configuration and server logs.</p>');
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
