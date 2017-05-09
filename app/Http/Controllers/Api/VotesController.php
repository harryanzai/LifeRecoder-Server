<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VotesController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth.api')->except([
            'index'
        ]);

    }


    private $models = [

        'comment.vote' => Comment::class
    ];

    /**
     * @api {post} /v1/comments/:comment/votes 赞当前的评论
     * @apiGroup Vote
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission none
     * @apiParam {int} comment 对应评论的id
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

        $modelClass::findOrFail($id)->vote();

        return $this->respondWithMessage('添加成功');
    }

    /**
     * @api {delete} /v1/comments/:commet/votes 取消点赞
     * @apiGroup Vote
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission none
     * @apiParam {int} commet 对应评论的id
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "message": "取消点赞成功"
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

        if ($model->isVoted){
            $model->unvote();
        }
        return $this->respondWithMessage('取消点赞成功');


    }
}
