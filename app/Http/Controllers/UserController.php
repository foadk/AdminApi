<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    private $headers = [

        'main' => [
            'users.id' => 'شناسه',
            'users.name' => 'نام',
            'users.last_name' => 'نام خانوادگی',
            'users.sex' => 'جنسیت',
            'users.mobile' => 'تلفن',
            'users.national_id' => 'کد ملی',
            'users.email' => 'ایمیل',
        ],

        'actions' => [
            'quick_edit' => 'ویرایش سریع',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
        ]

    ];

    public function datatable(Request $request)
    {
        $data = $this->buildDatatable(
            $request->all(),
            new User(),
            'users',
            null,
            array_map(function ($item) {return $item . ' as ' . $item;}, array_keys($this->headers['main']))
//            array_keys($this->headers['main'])
        );

        $data['headers'] = $this->headers;

        return response()->json($data, 206);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'email',
            'password' => 'required|min:3',
            'sex' => 'required',
            'national_id' => 'nullable',
            'mobile' => 'required'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        $this->messages[] = [
            'message' => 'کاربر جدید با موفقیت ایجاد شد.',
            'type' => 'success',
            'timeout' => 5000
        ];
        $data['messages'] = $this->messages;

        return $data;
    }

    public function edit(User $user)
    {
        return $user->toArray();
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'email',
            'password' => 'nullable',
            'sex' => 'required',
            'national_id' => 'nullable',
            'mobile' => 'required'
        ]);

        if(array_key_exists('password', $validatedData) && trim($validatedData['password']) == '') {
            unset($validatedData['password']);
        }

//        return $validatedData;

        $user->update($validatedData);

        $this->messages[] = [
            'message' => 'کاربر با موفقیت ویرایش شد.',
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
                'message' => 'کاربر با آیدی ' . $user->id . ' با موفقیت حذف شد.',
                'type' => 'success',
                'timeout' => 5000
            ];
        }

        $data['messages'] = $this->messages;

        if ($user->delete() === true) {
            return response()->json($data, 200);
        }
    }
}
