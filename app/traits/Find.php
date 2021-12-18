<?php
namespace app\traits;

use app\database\Connection;

trait Find
{
    public function find($id)
    {
        try {
            $conn = Connection::getConnection();
            $user = $conn->query("select * from ".$this->table." where id = $id");

            return $user->fetchObject(get_called_class());
        } catch (\Throwable $th) {
            print $th->getMessage();
        }
    }


    public function all()
    {
        try {
            $conn = Connection::getConnection();
            $user = $conn->query("select * from ".$this->table);

            return $user->fetchAll(PDO::FETCH_CLASS, get_called_class());
        } catch (\Throwable $th) {
            print $th->getMessage();
        }
    }
}
