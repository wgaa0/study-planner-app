<?php

$driver = 'sqlite'; // change this to 'mysql' if you want MySQL

return [
    'db' => [
        'driver' => $driver,
        'dsn'    => $driver === 'sqlite'
                        ? 'sqlite:' . __DIR__ . '/../db/study_manager.sqlite'
                        : 'mysql:host=127.0.0.1;dbname=study_manager;charset=utf8mb4',
        'user'   => $driver === 'mysql' ? 'root' : null,
        'pass'   => $driver === 'mysql' ? '' : null,
    ]
];
