<?php

namespace App\Services\Datatable;

use App\Contracts\DatatableInterface;

class MainDatatable implements DatatableInterface
{
    // Input fields
    protected $input;
    protected $resource;
    protected $tableName;
    protected $joins;
    protected $selects;

    // Result fields
    protected $resultRows;
    protected $pages;

    protected function setInput($input)
    {
        $this->input = $input;
    }

    protected function setResource($resource)
    {
        $this->resource = $resource;
    }

    protected function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    protected function setJoins($joins)
    {
        $this->joins = $joins;
    }

    protected function setSelects($selects)
    {
        $this->selects = array_map(
            function ($item) {
                return $item . ' as ' . $item;
            },
            array_keys($selects)
        );
    }

    public function buildDatatable($input, $resource, $tableName, $joins, $selects)
    {
        $this->setInput($input);
        $this->setResource($resource);
        $this->setTableName($tableName);
        $this->setJoins($joins);
        $this->setSelects($selects);
        $this->build();
        return $this->returnResults();
    }

    public function returnResults()
    {
        $data = [];

        $data['rows'] = $this->resultRows;
        $data['pages'] = $this->pages;

        return $data;
    }

    protected function build()
    {
        $rows = $this->resource;

        $rows = $this->joinTables($rows);
        $rows = $this->filter($rows);
        $rows = $this->sort($rows);
        $rows = $this->paginate($rows);
        $rows = $this->select($rows);

        $rows = $rows->get()->toArray();

        $this->resultRows = $rows;
    }

    protected function joinTables($rows)
    {
        if ($this->joins && count($this->joins)) {
            foreach ($this->joins as $join) {
                if ($join['joinType'] === 'OneToMany') {
                    $rows = $rows
                        ->join($join['table'], $this->tableName . '.' . $join['foreignKey'], '=', $join['table'] . '.id');
                }
            }
        }
        return $rows;
    }

    protected function filter($rows)
    {
        foreach ($this->input['filtered'] as $filtered) {
            $rows = $rows->whereRaw("cast(" . $filtered['id'] . " as text) ILIKE '%" . $filtered['value'] . "%'");
        }
        return $rows;
    }

    protected function sort($rows)
    {
        foreach ($this->input['sorted'] as $sorted) {
            $rows = $rows->orderBy($sorted['id'], $sorted['desc'] ? 'desc' : 'asc');
        }
        $rows = $rows->orderBy($this->tableName . '.id', 'DESC');
        return $rows;
    }

    protected function paginate($rows)
    {
        $pages = (int)ceil($rows->count() / $this->input['pageSize']);
        $skipPages = $this->input['page'];
        if ($this->input['page'] >= $pages) {
            $skipPages = $pages - 1;
        }
        $rows = $rows->skip($skipPages * $this->input['pageSize'])->take($this->input['pageSize']);

        $this->pages = $pages;

        return $rows;
    }

    protected function select($rows)
    {
        if ($this->selects) {
            $rows = $rows->select($this->selects);
        }
        return $rows;
    }
}