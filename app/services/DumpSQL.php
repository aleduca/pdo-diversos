<?php

namespace app\services;

class DumpSQL
{
    private static array $sqls = [];

    public static function add(string $sql)
    {
        self::$sqls[] = $sql;
    }

    public static function get()
    {
        if (empty(self::$sqls)) {
            throw new \Exception("To show dump pelase give me one query");
        }

        dd(self::$sqls);
    }
}
