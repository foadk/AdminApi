<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $messages = [];

    protected function buildDatatable($input, $resource, $tableName, $joins, $selects)
    {
        $data = [];
        $rows = $resource;

        // joining
        $rows = $this->joinTables($rows, $tableName, $joins);

        // filtering
        foreach ($input['filtered'] as $filtered) {
            $rows = $rows->whereRaw("cast(" . $filtered['id'] . " as text) ILIKE '%" . $filtered['value'] . "%'");
        }

        // sorting
        foreach ($input['sorted'] as $sorted) {
            $rows = $rows->orderBy($sorted['id'], $sorted['desc'] ? 'desc' : 'asc');
        }
        $rows = $rows->orderBy($tableName . '.id', 'DESC');

        // pagination
        $pages = (int)ceil($rows->count() / $input['pageSize']);
        $skipPages = $input['page'];
        if ($input['page'] >= $pages) {
            $skipPages = $pages - 1;
        }
        $rows = $rows->skip($skipPages * $input['pageSize'])->take($input['pageSize']);
        if ($selects) {
            $rows = $rows->select($selects);
        }
        $rows = $rows->get()->toArray();

//        return $rows;
        $data['rows'] = $rows;
        $data['pages'] = $pages;
        $data['table'] = $tableName; // needed in front end to get id (like table + '.id') for deleting rows and ...

        return $data;
    }

    private function joinTables($rows, $tableName, $joins) {

        if ($joins && count($joins)) {
            foreach ($joins as $join) {
                if ($join['joinType'] === 'OneToMany') {
                    $rows = $rows
                        ->join($join['table'], $tableName . '.' . $join['foreignKey'], '=', $join['table'] . '.id');
                }
            }
        }
        return $rows;
    }
}
