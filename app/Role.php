<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['title', 'position', 'display'];

    public function permissionGroups()
    {
        return $this->belongsToMany('App\PermissionGroup');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
