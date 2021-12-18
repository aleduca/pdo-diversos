<?php

namespace app\database\models;

use app\traits\Create;
use app\traits\Find;

class User extends Model
{
    protected $table = 'users';

    public function teste()
    {
        return 'teste';
    }
}
