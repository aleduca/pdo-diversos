<?php
namespace app\database;

use app\database\models\Model;
use Exception;

class Pagination
{
    private int $currentPage = 1;
    private int $totalPages;
    private int $linksPerPage = 5;
    private int $itemsPerPage = 10;
    private int $totalItems;
    private ?Filters $filters = null;
    private Model $model;
    private string $pageIndentifier = 'page';

    public function setFilters(Filters $filters)
    {
        $this->filters = $filters;
    }

    public function setItemsPerPage(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function setModel(string $model)
    {
        if (!class_exists($model)) {
            throw new Exception("Esse model {$model} não existe");
        }
        $this->model = new $model;
    }

    public function setPageIndentifier(string $identifier)
    {
        $this->pageIndentifier = $identifier;
    }

    private function calculations()
    {
        $this->model->setFilters($this->filters);
        $this->totalItems = $this->model->count();
        $this->currentPage = $_GET[$this->pageIndentifier] ?? 1;
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);

        return "limit {$this->itemsPerPage} offset {$offset}";
    }

    public function dump()
    {
        return $this->calculations();
    }
}
