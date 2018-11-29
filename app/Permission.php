<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['permission_gourp_id', 'title', 'position'];

    public function permissionGroup()
    {
        return $this->belongsTo('App\PermissionGroup');
    }
}
