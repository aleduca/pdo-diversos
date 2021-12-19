<?php

require '../vendor/autoload.php';

use app\database\Connection;
use app\database\models\User;
use app\services\DumpSQL;

try {
    Connection::open();

    $user = new User;

    $user->delete(169);
    $user->create([
        'firstName' => 'Alexandre',
        'lastName' => 'Cardoso',
        'email' => 'email@email.com.br',
        'password' => password_hash('123', PASSWORD_DEFAULT),
    ]);

    DumpSQL::get();
    Connection::close();
} catch (\Throwable $e) {
    //throw $th;
    Connection::rollback($e);
}
