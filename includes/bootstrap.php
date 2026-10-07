<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

if (PHP_SAPI !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
    session_name('mapmyuni_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/database.php';

function h(mixed $value): string
{
    return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_is_valid(mixed $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

function database_is_ready(): bool
{
    try {
        $pdo = db();
        $pdo->query('SELECT 1 FROM faculty LIMIT 1');
        $pdo->query('SELECT 1 FROM campus_places LIMIT 1');
        $pdo->query('SELECT 1 FROM contact_messages LIMIT 1');
        return true;
    } catch (Throwable $error) {
        return false;
    }
}

function database_setup_notice(): string
{
    return 'Start Apache and MySQL in XAMPP, import schema.sql in phpMyAdmin, then import the faculty CSV files. See README.md for the steps.';
}
