<?php

require '../vendor/autoload.php';


use app\database\Connection;
use app\database\models\User;

try {
    Connection::open();

    $user = new User;

    $user->delete(169);
    $user->create(['']);

    Connection::close();
} catch (\Throwable $e) {
    //throw $th;
    Connection::rollback($e);
}
