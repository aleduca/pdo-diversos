<?php

require '../vendor/autoload.php';

use app\database\Connection;
use app\database\Filters;
use app\database\models\User;
use app\database\Pagination;
use app\services\DumpSQL;
use League\Plates\Engine;

try {
    Connection::open();


    $filters = new Filters;
    $filters->join('posts', 'users.id', '=', 'posts.userId', 'left join');
    $filters->where("users.id", ">", 150);
    // $filters->orderBy("id", 'desc');


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
    $user->setFields('users.id,firstName,lastName, title');
    $usersFound = $user->all();

    dd($usersFound);

    // DumpSQL::get();
    Connection::close();

    $templates = new Engine('../app/views');

    echo $templates->render('home', ['users' => $usersFound,'links' => $pagination->links()]);
} catch (\Throwable $e) {
    //throw $th;
    Connection::rollback($e);
}
