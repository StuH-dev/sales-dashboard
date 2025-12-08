<?php

function loadEnv(string $filePath): void
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

loadEnv(__DIR__ . '/../.env');

class Database
{
    private static ?PDO $conn = null;

    public static function connection(): PDO
    {
        if (self::$conn === null) {
            $server   = getenv('DB_SERVER') ?: 'tcp:localhost,1433';
            $database = getenv('DB_DATABASE') ?: '';
            $username = getenv('DB_USERNAME') ?: '';
            $password = getenv('DB_PASSWORD') ?: '';

            if (empty($database) || empty($username)) {
                throw new RuntimeException('Database configuration is missing. Please check your .env file.');
            }

            $dsn = "sqlsrv:Server=$server;Database=$database";

            self::$conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$conn;
    }
}
