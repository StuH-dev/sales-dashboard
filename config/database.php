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
            $server   = getenv('DB_SERVER') ?: 'localhost\SQLEXPRESS';
            $database = getenv('DB_DATABASE') ?: 'sales-dashboard';
            $username = getenv('DB_USERNAME') ?: 'sa';
            $password = getenv('DB_PASSWORD') ?: 'Pcare2009';
            $port     = getenv('DB_PORT') ?: '1433';
            $trusted  = getenv('DB_TRUSTED_CONNECTION') ?: 'false';

            if (empty($database)) {
                throw new RuntimeException('Database name is required. Please set DB_DATABASE in your .env file.');
            }

            if (strtolower($trusted) === 'true' || strtolower($trusted) === 'yes') {
                $dsn = "sqlsrv:Server=$server,$port;Database=$database;TrustServerCertificate=1";
                $username = null;
                $password = null;
            } else {
                if (empty($username)) {
                    throw new RuntimeException('Database username is required. Please set DB_USERNAME in your .env file or use DB_TRUSTED_CONNECTION=true for Windows Authentication.');
                }
                $dsn = "sqlsrv:Server=$server,$port;Database=$database;TrustServerCertificate=1";
            }

            try {
                self::$conn = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT            => 30,
                    PDO::SQLSRV_ATTR_QUERY_TIMEOUT => 30,
                ]);

                self::$conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            } catch (PDOException $e) {
                throw new RuntimeException('Failed to connect to SQL Server Express: ' . $e->getMessage(), 0, $e);
            }
        }

        return self::$conn;
    }
}
