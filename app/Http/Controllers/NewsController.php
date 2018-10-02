<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private $headers = [

        'main' => [
            'id' => 'شناسه',
            'title' => 'عنوان',
            'description' => 'توضیحات',
            'position' => 'موقعیت',
            'display' => 'نمایش',
        ],

        'actions' => [
            'quick_edit' => 'ویرایش سریع',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
        ]

    ];

    public function datatable(Request $request)
    {
        $data = $this->buildDatatable($request->all(), new News());

        $data['headers'] = $this->headers;

        return response()->json($data, 206);
    }

    public function delete(News $news)
    {
        if ($news && $news->id) {
            $this->messages[] = [
                'message' => 'خبر با آیدی ' . $news->id . ' با موفقیت حذف شد.',
                'type' => 'success',
                'timeout' => 5000
            ];
        } else {
            abort(404);
        }

        $data['messages'] = $this->messages;

        if ($news->delete() === true) {
            return response()->json($data, 200);
        } else {
            abort(500);
        }
    }
}
