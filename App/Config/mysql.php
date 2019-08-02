<?php

return [
    'debug_log' => true, // 是否打印sql日志
    'default'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql_test_host').";dbname=test",
        'username'          => getenv('mysql_test_user'),
        'password'          => getenv('mysql_test_pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'd_insurance'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql_host').";dbname=d_insurance",
        'username'          => getenv('mysql_user'),
        'password'          => getenv('mysql_pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'd_hf'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql_host').";dbname=d_hf",
        'username'          => getenv('mysql_user'),
        'password'          => getenv('mysql_pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'd_hnb'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql_host').";dbname=d_hnb",
        'username'          => getenv('mysql_user'),
        'password'          => getenv('mysql_pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ],
    'd_crm2'      => [
        'max_connect_count' => 10,
        'dns'               => "mysql:host=".getenv('mysql_host').";dbname=d_crm2",
        'username'          => getenv('mysql_user'),
        'password'          => getenv('mysql_pass'),
        'ops'               => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false
        ]
    ]
];
