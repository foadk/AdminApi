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

    public function delete(User $user) {

        $data['messages ']= [];

        if($user) {
            $data['messages'][] = [
                'message' => 'کاربر با آیدی ' . $user->id . ' با موفقیت حذف شد.',
                'type' => 'success',
                'timeout' => 5000
            ];
        }

        if($user->delete() === true) {
            return response()->json($data, 200);
        }
    }
}
