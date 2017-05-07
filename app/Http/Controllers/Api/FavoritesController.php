<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Gallery;
use Illuminate\Http\Request;

class FavoritesController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth.api')->except([
            'index'
        ]);

    }



    private $models = [

        'gallery.favorite' => Gallery::class,
        'comment.favorite' => Comment::class
    ];

    public function store(Request $request,$id)
    {

        $modelClass = $this->models[$request->route()->getName()];

        $modelClass::findOrFail($id)->favorite();

        return $this->respondWithMessage('添加成功');
    }

    public function destroy(Request $request,$id)
    {
        $modelClass = $this->models[$request->route()->getName()];
        $model = $modelClass::findOrFail($id);

        if ($model->isFavorited){
            $model->unfavorite();
        }
        return $this->respondWithMessage('取消喜欢成功');


    }
}
