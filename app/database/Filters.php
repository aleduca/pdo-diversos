<?php
namespace app\database;

class Filters
{
    private array $filters = [];

    public function where(string $field, string $operator, mixed $value, string $logic = '')
    {
        $format = '';
        if (is_array($value)) {
            $format.="(".'\''.implode("','", $value).'\''.')';
        } elseif (is_string($value)) {
            $format.="'{$value}'";
        } elseif (is_bool($value)) {
            $format.= $value ? 1 : 0;
        } else {
            $format .= $value;
        }

        $format = strip_tags($format);

        $this->filters['where'][] = "{$field} {$operator} {$format} {$logic}";
    }

    public function limit(int $limit)
    {
        $this->filters['limit'] = " limit {$limit}";
    }
    
    public function orderBy(string $field, string $order = 'asc')
    {
        $this->filters['order'] = " order by {$field} {$order}";
    }

    public function join(string $foreignTable, string $joinTable1, string $operator, string $joinTable2, string $joinType = 'inner join')
    {
        $this->filters['join'][] = "{$joinType} {$foreignTable} on {$joinTable1} {$operator} {$joinTable2}";
    }

    public function dump()
    {
        $query = !empty($this->filters['join']) ? implode(" ", $this->filters['join']) : '';
        $query.= !empty($this->filters['where']) ? " where ".implode(" ", $this->filters['where']) : '';
        $query.= $this->filters['order'] ?? '';
        $query.= $this->filters['limit'] ?? '';

        // dd($query);

        return $query;
    }
}
