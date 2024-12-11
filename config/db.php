<?php

//////////////////////////////////////
$host = $_SERVER['HTTP_HOST']; // Get the current host

if ($host === 'localhost') {
    // Localhost environment
    return [
        'class' => 'yii\db\Connection',
        'dsn' => "mysql:host=localhost;dbname=pos",
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ];
} elseif ($host === 'pos.wuaze.com') {
    // Production environment for wuaze.com
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=sql110.infinityfree.com;dbname=if0_37114096_pos',
        'username' => 'if0_37114096',
        'password' => 'QcIDYuIrKJ',
        'charset' => 'utf8',
    ];
} else {
    // Default fallback or other environments
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=mariadb;dbname=pos',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ];
}

