<?php

require '../vendor/autoload.php';

use app\database\Connection;
use app\database\Filters;
use app\database\models\User;
use app\database\Pagination;
use app\services\DumpSQL;

try {
    Connection::open();

    $filters = new Filters;
    $filters->where("id", ">", 150);
    $filters->orderBy("id", 'desc');


    $pagination = new Pagination;
    $pagination->setModel(User::class);
    $pagination->setItemsPerPage(5);
    $pagination->setFilters($filters);
    // $pagination->dump();

    // select * from users where id IN (1,2,3,4,5,6)

    // $user->setFilters($filters);
    $user = new User;
    $user->setPagination($pagination);
    $user->setFilters($filters);
    $user->setFields('id,firstName,lastName');
    $usersFound = $user->all();

    // DumpSQL::get();

    dd($usersFound);

    // $user->delete(169);
    // $user->create([
    //     'firstName' => 'Alexandre',
    //     'lastName' => 'Cardoso',
    //     'email' => 'email@email.com.br',
    //     'password' => password_hash('123', PASSWORD_DEFAULT),
    // ]);

    DumpSQL::get();
    Connection::close();
} catch (\Throwable $e) {
    //throw $th;
    Connection::rollback($e);
}
