<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $fillable = ['title', 'position', 'display'];

    public function permissions()
    {
        return $this->hasMany('App\Permission');
    }
}
