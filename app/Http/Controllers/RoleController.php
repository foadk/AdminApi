<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\PermissionGroup;

class RoleController extends AdminController
{
    private $headers = [

        'main' => [
            'roles.id' => 'شناسه',
            'roles.title' => 'عنوان',
            'roles.position' => 'موقعیت',
        ],

        'actions' => [
            'quick_edit' => 'ویرایش سریع',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
        ]

    ];

    private $tableName = 'roles';

    public function datatable(Request $request)
    {
        $data = $this->Datatable->buildDatatable(
            $request->all(),
            new Role(),
            $this->tableName,
            null,
            $this->headers['main']
        );

        $data['headers'] = $this->headers;
        $data['table'] = $this->tableName;

        return response()->json($data, 206);
    }

    public function create()
    {
        $permissionGroups = PermissionGroup::select(['id', 'title'])->get()->toArray();

        $data['permissionGroups'] = $permissionGroups;

        return $data;
    }

    public function store(Request $request)
    {
        $validatedData = $this->removeNulls($this->validateData($request));

        $role = Role::create($validatedData);

        $role->permissionGroups()->sync($validatedData['permission_group_ids']);

        $this->messages[] = [
            'message' => 'گروه کاربری جدید با موفقیت ایجاد شد.',
            'type' => 'success',
            'timeout' => 5000
        ];
        $data['messages'] = $this->messages;

        return $data;
    }

    public function edit(Role $role)
    {
        $data['permissionGroups'] = PermissionGroup::select(['id', 'title'])->get()->toArray();

        $data['fields'] = $role->toArray();

        $data['fields']['permission_group_ids'] = $role->permissionGroups->pluck('id')->toArray();

        return $data;
    }

    public function update(Request $request, Role $role)
    {
        $validatedData = $this->removeNulls($this->validateData($request));

        $role->permissionGroups()->sync($validatedData['permission_group_ids']);

        $role->update($validatedData);

        $this->messages[] = [
            'message' => 'گروه کاربری با موفقیت ویرایش شد.',
            'type' => 'success',
            'timeout' => 5000
        ];
        $data['messages'] = $this->messages;

        return $data;
    }

    public function delete(Role $role)
    {
        if ($role) {
            $this->messages[] = [
                'message' => 'گروه کاربری با آیدی ' . $role->id . ' با موفقیت حذف شد.',
                'type' => 'success',
                'timeout' => 5000
            ];
        }

        $data['messages'] = $this->messages;

        if ($role->delete() === true) {
            return response()->json($data, 200);
        }
    }

    private function validateData($request)
    {
        return $request->validate([
            'title' => 'required',
            'position' => 'nullable',
            'display' => 'nullable',
            'permission_group_ids' => 'required'
        ]);
    }

    private function removeNulls($items)
    {
        foreach ($items as $key => $item) {
            if (!$item) unset($items[$key]);
        }
        return $items;
    }
}
