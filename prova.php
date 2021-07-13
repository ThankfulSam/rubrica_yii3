<?php
include 'vendor/autoload.php';

use Spiral\Database;
use Spiral\Database\Driver\MySQL\MySQLDriver;

$dbal = new Database\DatabaseManager(
    new Database\Config\DatabaseConfig([
        'default'     => 'default',
        'databases'   => [
            'default' => ['connection' => 'mysql']
        ],
        'connections' => [
            'mysql' => [
                'driver'  => MySQLDriver::class,
                'connection' => 'mysql:host=127.0.0.1;dbname=rubrica',
                'username'   => 'root',
                'password'   => '',
            ]
        ]
    ])
    );

print_r($dbal->database('default')->hasTable('new_user'));