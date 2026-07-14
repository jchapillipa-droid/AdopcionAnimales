<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('SESSION_TIMEOUT')) {
    define('SESSION_TIMEOUT', 1800);
}

if (!defined('APP_BASE')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    define('APP_BASE', $scriptDir === '/' ? '' : $scriptDir);
}

function h($valor): string
{
    if ($valor instanceof DateTimeInterface) {
        return $valor->format('d/m/Y');
    }

    if ($valor === null) {
        return '';
    }

    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function date_fmt($valor): string
{
    if ($valor instanceof DateTimeInterface) {
        return $valor->format('d/m/Y');
    }

    if ($valor === null || $valor === '') {
        return '';
    }

    $timestamp = strtotime((string)$valor);
    return $timestamp ? date('d/m/Y', $timestamp) : (string)$valor;
}

function url(string $action, array $params = []): string
{
    $query = array_merge(['action' => $action], $params);
    return APP_BASE . '/index.php?' . http_build_query($query);
}

function redirect_to(string $action, array $params = []): void
{
    header('Location: ' . url($action, $params));
    exit;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function flash_get(): array
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool
{
    return is_logged_in() && (($_SESSION['user']['rol_nombre'] ?? '') === 'Administrador');
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash_set('error', 'Debes iniciar sesión para continuar.');
        redirect_to('login');
    }
}

function require_admin(): void
{
    if (!is_admin()) {
        flash_set('error', 'No tienes permisos para acceder a esta sección.');
        redirect_to('home');
    }
}

function check_session_timeout(): void
{
    if (is_logged_in()) {
        $last = $_SESSION['last_activity'] ?? time();

        if ((time() - $last) > SESSION_TIMEOUT) {
            session_unset();
            session_destroy();
            session_start();
            flash_set('error', 'Tu sesión expiró por inactividad.');
            redirect_to('login');
        }

        $_SESSION['last_activity'] = time();
    }
}

function animal_image(?string $imagen): string
{
    $imagen = trim((string)$imagen);

    if ($imagen === '') {
        return APP_BASE . '/public/images/placeholder.svg';
    }

    if (filter_var($imagen, FILTER_VALIDATE_URL)) {
        return $imagen;
    }

    $uploadPath = __DIR__ . '/../uploads/' . $imagen;
    if (file_exists($uploadPath)) {
        return APP_BASE . '/uploads/' . rawurlencode($imagen);
    }

    $publicPath = __DIR__ . '/../public/images/' . $imagen;
    if (file_exists($publicPath)) {
        return APP_BASE . '/public/images/' . rawurlencode($imagen);
    }

    return APP_BASE . '/public/images/placeholder.svg';
}

check_session_timeout();