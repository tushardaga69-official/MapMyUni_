<?php
declare(strict_types=1);

function db(): PDO
{
    static $connection = null;
    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('MAPMYUNI_DB_HOST') ?: '127.0.0.1';
    $name = getenv('MAPMYUNI_DB_NAME') ?: 'mapmyuni';
    $user = getenv('MAPMYUNI_DB_USER') ?: 'root';
    $password = getenv('MAPMYUNI_DB_PASS') ?: '';
    $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

    $connection = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $connection;
}
