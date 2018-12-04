<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

class AdminsController extends AdminController
{
    private $headers = [

        'main' => [
            'users.id' => 'شناسه',
            'users.name' => 'نام',
            'users.last_name' => 'نام خانوادگی',
            'users.email' => 'ایمیل',
        ],

        'actions' => [
            'quick_edit' => 'ویرایش سریع',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
        ]

    ];

    private $tableName = 'users';

    public function datatable(Request $request)
    {
        $data = $this->Datatable->buildDatatable(
            $request->all(),
            new User(),
            $this->tableName,
            null,
            [['users.is_admin', '=', true]],
            $this->headers['main']
        );

        $data['headers'] = $this->headers;
        $data['table'] = $this->tableName;

        return response()->json($data, 206);
    }

    public function create()
    {
        $data['roles'] = Role::select(['id', 'title'])->get()->toArray();

        return $data;
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateData($request);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $validatedData['is_admin'] = true;

        $admin = User::create($validatedData);

        $admin->roles()->sync($validatedData['role_ids']);

        $this->messages[] = [
            'message' => 'مدیر جدید با موفقیت ایجاد شد.',
            'type' => 'success',
            'timeout' => 5000
        ];
        $data['messages'] = $this->messages;

        return $data;
    }

    public function edit(User $user)
    {
        $data['roles'] = Role::select(['id', 'title'])->get()->toArray();

        $data['fields'] = $user->toArray();

        $data['fields']['role_ids'] = $user->roles->pluck('id')->toArray();;

        return $data;
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $this->validateData($request, ['password' => 'nullable|min:3']);

        if (array_key_exists('password', $validatedData) && trim($validatedData['password']) == '') {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        $user->roles()->sync($validatedData['role_ids']);

        $this->messages[] = [
            'message' => 'مدیر با موفقیت ویرایش شد.',
            'type' => 'success',
            'timeout' => 5000
        ];
        $data['messages'] = $this->messages;

        return $data;
    }

    public function delete(User $user)
    {
        if ($user) {
            $this->messages[] = [
                'message' => 'مدیر با آیدی ' . $user->id . ' با موفقیت حذف شد.',
                'type' => 'success',
                'timeout' => 5000
            ];
        }

        $data['messages'] = $this->messages;

        if ($user->delete() === true) {
            return response()->json($data, 200);
        }
    }

    private function validateData($request, $additionalRules = null)
    {
        $validationRules = [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'email',
            'password' => 'required|min:3',
            'role_ids' => 'required'
        ];

        if ($additionalRules) $validationRules = array_merge($validationRules, $additionalRules);

        return $request->validate($validationRules);
    }
}
