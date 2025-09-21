<?php

class DB {
    private static $pdo;

    public static function get() {
        if (!self::$pdo) {
            $conf = include __DIR__ . '/../../config/config.php';
            $c = $conf['db'];

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO($c['dsn'], $c['user'], $c['pass'], $options);
        }
        return self::$pdo;
    }
}