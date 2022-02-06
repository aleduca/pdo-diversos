<?php

namespace app\database\models;

use app\traits\Create;

class Post extends Model
{
    protected $table = 'posts';

    public function post()
    {
        return 'post';
    }
}
