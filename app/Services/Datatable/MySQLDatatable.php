<?php

namespace App\Services\Datatable;

class MySQLDatatable extends MainDatatable {

    protected function filter($rows)
    {
        foreach ($this->input['filtered'] as $filtered) {
            $rows = $rows->whereRaw($filtered['id'] . " LIKE '%" . $filtered['value'] . "%'");
        }
        return $rows;
    }
}