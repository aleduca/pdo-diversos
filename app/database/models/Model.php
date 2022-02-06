<?php

namespace app\database\models;

use app\database\Connection;
use app\database\Filters;
use app\database\Pagination;
use app\services\DumpSQL;
use PDO;

abstract class Model
{
    // private static $conn;

    // public static function setConnection($conn)
    // {
    //     self::$conn = $conn;
    // }

    private string $filters = '';
    private string $pagination = '';
    private string $fields = '*';

    public function setFilters(Filters $filters)
    {
        if ($filters) {
            $this->filters = $filters->dump();
        }
    }

    public function setPagination(Pagination $pagination)
    {
        if ($pagination) {
            $this->pagination = $pagination->dump();
        }
    }

    public function setFields(string $fields)
    {
        $this->fields = $fields;
    }

    public function find($id = '')
    {
        try {
            $conn = Connection::getConnection();

            $sql = (empty($this->filters)) ?
                "select {$this->fields} from ".$this->table." where id = $id" :
                "select {$this->fields} from {$this->table} {$this->filters}";

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
            $sql = "select {$this->fields} from {$this->table} {$this->filters} {$this->pagination}";

            DumpSQL::add($sql);

            $data = $conn->query($sql);

            return $data->fetchAll(PDO::FETCH_CLASS, get_called_class());
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

    public function count()
    {
        try {
            $conn = Connection::getConnection();
            $sql = "select {$this->fields} from {$this->table} {$this->filters}";

            DumpSQL::add($sql);

            $data = $conn->query($sql);

            return $data->rowCount();
        } catch (\Throwable $th) {
            print $th->getMessage();
        }
    }
}
