<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\PermissionGroup;
use Illuminate\Support\Facades\Route;

class PermissionController extends AdminController
{
    private $headers = [

        'main' => [
            'permissions.id' => 'شناسه',
            'permissions.title' => 'عنوان',
            'permissions.position' => 'موقعیت',
            'permission_groups.title' => 'گروه',
        ],

        'actions' => [
            'quick_edit' => 'ویرایش سریع',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
        ]

    ];

    private $tableName = 'permissions';

    public function datatable(Request $request)
    {
        $data = $this->Datatable->buildDatatable(
            $request->all(),
            new Permission(),
            $this->tableName,
            [
                [
                    'joinType' => 'OneToMany',
                    'table' => 'permission_groups',
                    'foreignKey' => 'permission_group_id',
                ]
            ],
            $this->headers['main']
        );

        $data['headers'] = $this->headers;
        $data['table'] = $this->tableName;

        return response()->json($data, 206);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateData($request);

        $permissionGroup = PermissionGroup::firstOrCreate(['title' => $validatedData['permission_group_title']]);

        $permissionGroup->permissions()->create($validatedData);

        $this->messages[] = [
            'message' => 'دسترسی جدید با موفقیت ایجاد شد.',
            'type' => 'success',
            'timeout' => 5000
        ];
        $data['messages'] = $this->messages;

        return $data;
    }

    public function edit(Permission $permission)
    {
        $data['title'] = $permission->title;
        $data['permission_group_title'] = $permission->permissionGroup->title;

        return $data;
    }

    public function update(Request $request, Permission $permission)
    {
        $validatedData = $this->validateData($request);

        $permissionGroup = PermissionGroup::firstOrCreate(['title' => $validatedData['permission_group_title']]);

        $permission->permissionGroup()->associate($permissionGroup);
        $permission->update($validatedData);

        $this->messages[] = [
            'message' => 'دسترسی با موفقیت ویرایش شد.',
            'type' => 'success',
            'timeout' => 5000
        ];
        $data['messages'] = $this->messages;

        return $data;
    }

    public function delete(Permission $permission)
    {
        if ($permission && $permission->id) {
            $this->messages[] = [
                'message' => 'دسترسی با آیدی ' . $permission->id . ' با موفقیت حذف شد.',
                'type' => 'success',
                'timeout' => 5000
            ];
        } else {
            abort(404);
        }

        $data['messages'] = $this->messages;

        if ($permission->delete() === true) {
            return response()->json($data, 200);
        } else {
            abort(500);
        }
    }

    public function sync()
    {
        $routes = array();
        $undefinedPermissionGroup = PermissionGroup::firstOrCreate(['title' => 'undefined']);
        $allPermissions = Permission::pluck('title')->toArray();
        $routeCollection = Route::getRoutes();
        foreach ($routeCollection as $route) {
            $routes[] = ['title' => $route->getName(), 'permission_group_id' => $undefinedPermissionGroup->id];
        }
        $routes = array_filter($routes, function ($item) use($allPermissions) {
            return ('admin' === explode('.', $item['title'])[0]) && (!in_array($item['title'], $allPermissions));
        });
        Permission::insert($routes);
    }

    private function validateData($request)
    {
        return $request->validate([
            'title' => 'required',
            'permission_group_title' => 'required',
        ]);
    }
}
