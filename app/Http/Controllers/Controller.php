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

    protected function buildDatatable($input, $resource) {

        $data = [];
        $rows = $resource;

        // filtering
        foreach ($input['filtered'] as $filtered) {
            $rows = $rows->whereRaw("cast(" . $filtered['id'] . " as text) ILIKE '%" . $filtered['value'] . "%'");
        }

        // sorting
        foreach ($input['sorted'] as $sorted) {
            $rows = $rows->orderBy($sorted['id'], $sorted['desc'] ? 'desc' : 'asc');
        }
        $rows = $rows->orderBy('id', 'DESC');

        // pagination
        $pages = (int)ceil($rows->count() / $input['pageSize']);
        $skipPages = $input['page'];
        if ($input['page'] >= $pages) {
            $skipPages = $pages - 1;
        }
        $rows = $rows->skip($skipPages * $input['pageSize'])->take($input['pageSize'])->get()->toArray();

        $data['rows'] = $rows;
        $data['pages'] = $pages;

        return $data;
    }
}
