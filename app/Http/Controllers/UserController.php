<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    private $headers = [

        'main' => [
            'id' => 'شناسه',
            'name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'sex' => 'جنسیت',
            'mobile' => 'تلفن',
            'national_id' => 'کد ملی',
            'email' => 'ایمیل',
        ],

        'actions' => [
            'quick_edit' => 'ویرایش سریع',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
        ]

    ];

    public function datatable(Request $request)
    {
        $data = $this->buildDatatable($request->all(), new User());

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
