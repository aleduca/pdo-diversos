<?php

namespace app\database\models;

use app\database\Connection;
use app\services\DumpSQL;
use PDO;

abstract class Model
{
    // private static $conn;

    // public static function setConnection($conn)
    // {
    //     self::$conn = $conn;
    // }

    public function find($id)
    {
        try {
            $conn = Connection::getConnection();
            $sql = "select * from ".$this->table." where id = $id";

            DumpSQL::add($sql);

            $user = $conn->query($sql);

            return $user->fetchObject(get_called_class());
        } catch (\Throwable $th) {
            print $th->getMessage();
        }
    }


    public function all()
    {
        try {
            $conn = Connection::getConnection();
            $sql = "select * from ".$this->table;

            DumpSQL::add($sql);

            $user = $conn->query($sql);

            return $user->fetchAll(PDO::FETCH_CLASS, get_called_class());
        } catch (\Throwable $th) {
            print $th->getMessage();
        }
    }

    public function create(array $data)
    {
        $conn = Connection::getConnection();
        $sql = "insert into ".$this->table."(".implode(',', array_keys($data)).")values(:".implode(',:', array_keys($data)).")";
        DumpSQL::add($sql);
        $prepare = $conn->prepare($sql);
        return $prepare->execute($data);
    }

    public function delete(int $id)
    {
        $conn = Connection::getConnection();
        $sql = "delete from ".$this->table. " where id = :id";
        DumpSQL::add($sql);
        $prepare = $conn->prepare($sql);
        return $prepare->execute(['id' => $id]);
    }
}
