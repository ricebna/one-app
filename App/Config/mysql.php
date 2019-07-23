<?php

return [
    'debug_log' => true, // 是否打印sql日志
    'default'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql.host').";dbname=test",
        'username'          => getenv('mysql.user'),
        'password'          => getenv('mysql.pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'test2'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql.host').";dbname=test2",
        'username'          => getenv('mysql.user'),
        'password'          => getenv('mysql.pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'd_insurance'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql.host').";dbname=d_insurance",
        'username'          => getenv('mysql.user'),
        'password'          => getenv('mysql.pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ]
];
