<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Gallery;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommentsController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth.api')->except([
            'index'
        ]);
    }


    /**
     * @api {get} /v1/{galleries}/{gallery}/comments 获取单个图片集或文章的所有评论
     * @apiGroup Comment
     * @apiPermission none
     * @apiParam {int} gallery 图片集的id
     * @apiParam {int} galleries 传galleries获取图片集评论,传articles获取文章评论
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "data": [
     *              {
     *               "id": 63,
     *               "body": "Qui recusandae voluptatum quia necessitatibus.",
     *               "create_at": "2017-05-06 22:34:30",
     *               "votesCount": 0,
     *               "isVoted": false,
     *               "user": {
     *                   "id": 86,
     *                   "nickname": "隋帅",
     *                   "email": "adipisci.cum@example.net",
     *                   "avatar": "http://lorempixel.com/400/400/?60392",
     *                   "gender": "未设置"
     *                  }
     *              }
     *            ],
     *           "pagination": {
     *           "total": 120,
     *           "count": 1,
     *           "per_page": 1,
     *           "current_page": 3,
     *           "total_pages": 120
     *           }
     *       }
     */
    public function index($commentableId)
    {

        $limit = Input::get('limit') ?: 15;

        $comments = [];

        $galleryRoute = route('gallery.comments',$commentableId);

        if ($galleryRoute == \request()->url()){
            $comments = Gallery::find($commentableId)->comments()->paginate($limit);

        }

        return $this->respondWithPaginator($comments,new CommentTransformer);

    }



    /**
     * @api {post} /v1/commentable/{commentable}/comments 给时光集或者文章评论
     * @apiGroup Comment
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission none
     * @apiParam {int} commentable 对应图片集或文章的id
     * @apiParam {string} comment_type 评论的类型 文章:articles,图片集:galleries
     * @apiParam {string} body 评论的内容
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
     *    "code": 400,
     *    "message": "评论类容不能为空"
     *   }
     */
    public function store(Request $request,$commentableId)
    {
        $types = Comment::$commentables;
        $this->validate($request,[
            'comment_type' => 'required|in:'.implode(',',$types),
            'body' => 'required',
        ],[
            'comment_type.required' => '评论类型不能为空',
            'comment_type.in' => '评论类型错误',
            'body.required' => '评论不能为空'
        ]);

        $toComment = Comment::toComment($request,$commentableId);
        if (is_null($toComment)){
            return $this->responseNotFond('没有找到评论的模型');
        }

        $toComment->comments()->create($request->all());

        return $this->respondWithMessage('添加评论成功');

    }


    /**
     * @api {delete} /v1/comments/:id 删除评论
     * @apiGroup Comment
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission none
     * @apiParam {int} id 要删除评论的id
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "message": "删除成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *   {
     *    "status": "error",
     *    "code": 403,
     *    "message": "没有此权限"
     *   }
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('destroy',$comment);

        $comment->delete();

        return $this->respondWithMessage('删除成功');

    }


}
