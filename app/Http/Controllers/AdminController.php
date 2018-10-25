<?php

namespace App\Http\Controllers;

use App\Contracts\DatatableInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $Datatable;

    public function __construct(DatatableInterface $datatableInstance)
    {
        $this->Datatable = $datatableInstance;
    }
}
