<?php

namespace App\Services\Authorization;

use App\User;

class PermissionList
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getPermissionList()
    {
        $permissions = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->join('permission_group_role', 'roles.id', '=', 'permission_group_role.role_id')
            ->join('permission_groups', 'permission_group_role.permission_group_id', '=', 'permission_groups.id')
            ->join('permissions', 'permission_groups.id', '=', 'permissions.permission_group_id')
            ->where('users.id', $this->user->id)
            ->distinct()->pluck('permissions.title')->toArray();

        return $permissions;
    }
}