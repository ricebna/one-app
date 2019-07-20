<?php

return [
    'debug_log' => true, // 是否打印sql日志
    'default'      => [
        'max_connect_count' => 10,
        'dns'               => env('mysql.test.dns', 'mysql:host=127.0.0.1;dbname=test'),
        'username'          => env('mysql.test.username', 'root'),
        'password'          => env('mysql.test.password', ''),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'test2'      => [
        'max_connect_count' => 10,
        'dns'               => env('mysql.test2.dns'),
        'username'          => env('mysql.test2.username'),
        'password'          => env('mysql.test2.password'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'd_insurance'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".env('mysql.host').";dbname=d_insurance",
        'username'          => env('mysql.user'),
        'password'          => env('mysql.pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ]
];
