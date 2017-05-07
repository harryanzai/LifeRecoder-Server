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

    /**
     * @api {post} /v1/galleries/:gallery/favorites 添加喜欢的图集
     * @apiGroup Favorite
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission none
     * @apiParam {int} gallery 对应图片集的id
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "message": "添加成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *   {
     *    "status": "error",
     *    "code": 404,
     *    "message": "没有找到该资源"
     *   }
     */
    public function store(Request $request,$id)
    {

        $modelClass = $this->models[$request->route()->getName()];

        $modelClass::findOrFail($id)->favorite();

        return $this->respondWithMessage('添加成功');
    }

    /**
     * @api {delete} /v1/galleries/:gallery/favorites 取消喜欢的图集
     * @apiGroup Favorite
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission none
     * @apiParam {int} gallery 对应图片集的id
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "message": "取消喜欢成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *   {
     *    "status": "error",
     *    "code": 404,
     *    "message": "没有找到该资源"
     *   }
     */
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
