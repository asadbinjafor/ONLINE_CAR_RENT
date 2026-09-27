<?php
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Storage.php';
require_once __DIR__ . '/Auth.php';

Security::initSession();
Security::headers();
ensureUploadDirs();
Auth::tryRememberLogin();

spl_autoload_register(function (string $class): void {
    foreach ([
        ROOT_DIR . '/controllers/' . $class . '.php',
        ROOT_DIR . '/models/' . $class . '.php',
    ] as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

function view(string $name, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $authUser = Auth::user();
    $viewFile = ROOT_DIR . '/views/' . $name . '.php';
    if (!is_file($viewFile)) {
        http_response_code(500);
        die('View not found: ' . Security::e($name));
    }
    require ROOT_DIR . '/views/layouts/header.php';
    require $viewFile;
    require ROOT_DIR . '/views/layouts/footer.php';
}

/** App page URL (no .htaccess — routes via index.php?route=) */
function app_url(string $path = '/'): string
{
    $index = rtrim(BASE_URL, '/') . '/index.php';
    $path = '/' . trim($path, '/');
    if ($path !== '/') {
        $path = rtrim($path, '/') ?: '/';
    }
    if ($path === '/') {
        return $index;
    }
    return $index . '?route=' . rawurlencode($path);
}

function app_link(string $path, array $query = []): string
{
    $url = app_url($path);
    if ($query) {
        $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
    }
    return $url;
}

function redirect(string $path, array $query = []): void
{
    header('Location: ' . app_link($path, $query));
    exit;
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function isMember(): bool
{
    $u = Auth::user();
    return $u && $u['role'] === 'member';
}

function isAdmin(): bool
{
    $u = Auth::user();
    return $u && $u['role'] === 'admin';
}
