<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private $headers = [

        'main' => [
            'news.id' => 'شناسه',
            'news.title' => 'عنوان',
            'news.description' => 'توضیحات',
            'news.position' => 'موقعیت',
            'news.display' => 'نمایش',
            'news_cats.title' => 'گروه',
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
            new News(),
            'news',
            [
                [
                    'joinType' => 'OneToMany',
                    'table' => 'news_cats',
                    'foreignKey' => 'news_cat_id',
                ]
            ],
            array_map(
                function ($item) {return $item . ' as ' . $item;},
                array_keys($this->headers['main'])
            )
        );

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
