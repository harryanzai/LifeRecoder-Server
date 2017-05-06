<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Gallery;
use App\Transformers\CommentTransformer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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


    // 获取评论数据
    public function index($commentableId)
    {

        $limit = Input::get('limit') ?: 15;

        $galleryRoute = route('gallery.comments',$commentableId);

        if ($galleryRoute == \request()->url()){
            $comments = Gallery::find($commentableId)->comments()->paginate($limit);
            return $this->respondWithPaginator($comments,new CommentTransformer);
        }

        return $commentableId;


    }



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


}
