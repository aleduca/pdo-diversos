<?php

namespace app\database;

use app\services\Logger;
use app\services\LogTXT;
use Exception;
use PDO;

final class Connection
{
    private static $conn = null;
    private static bool $isTransaction = false;

    private function __construct()
    {
    }

    public static function open(bool $isTransaction = false)
    {
        if (!self::$conn) {
            self::$conn = new PDO("mysql:host=localhost;dbname=lumen", "root", "", [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ

            ]);
        }


        if ($isTransaction) {
            self::$conn->beginTransaction();
            self::$isTransaction = true;
        }

        return self::$conn;
    }

    public static function getConnection()
    {
        if (self::$conn) {
            return self::$conn;
        }
    }


    public static function close()
    {
        if (self::$isTransaction) {
            self::$conn->commit();
        }

        self::$conn = null;
    }

    public static function rollback(Exception $e)
    {
        if (self::$isTransaction) {
            self::$conn->rollback();
        }

        print $e->getMessage();
    }
}
