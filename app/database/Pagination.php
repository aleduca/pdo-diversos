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

    public function links()
    {
        $links = '<ul class="pagination">';


        if ($this->currentPage > 1) {
            $previous = $this->currentPage - 1;
            $linkPage = http_build_query(array_merge($_GET, [$this->pageIndentifier => $previous]));
            $first = http_build_query(array_merge($_GET, [$this->pageIndentifier => 1]));
            $links.="<li class='page-item'><a href='?{$first}' class='page-link'>Primeira</a></li>";
            $links.="<li class='page-item'><a href='?{$linkPage}' class='page-link'>Anterior</a></li>";
        }

        for ($i=$this->currentPage - $this->linksPerPage;$i <= $this->currentPage + $this->linksPerPage;$i++) {
            if ($i > 0 && $i <= $this->totalPages) {
                $class = $this->currentPage === $i ? 'active' : '';
                $linkPage = http_build_query(array_merge($_GET, [$this->pageIndentifier => $i]));
                $links.="<li class='page-item {$class}'><a href='?{$linkPage}' class='page-link'>{$i}</a></li>";
            }
        }

        if ($this->currentPage < $this->totalPages) {
            $next = $this->currentPage + 1;
            $linkPage = http_build_query(array_merge($_GET, [$this->pageIndentifier => $next]));
            $last = http_build_query(array_merge($_GET, [$this->pageIndentifier => $this->totalPages]));
            $links.="<li class='page-item'><a href='?{$linkPage}' class='page-link'>Próxima</a></li>";
            $links.="<li class='page-item'><a href='?{$last}' class='page-link'>Última</a></li>";
        }

        $links.="</ul>";

        return $links;
    }
}
