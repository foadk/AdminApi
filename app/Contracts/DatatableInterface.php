<?php

namespace App\Contracts;

Interface DatatableInterface
{
    public function buildDatatable($input, $resource, $tableName, $joins, $wheres, $selects);
}