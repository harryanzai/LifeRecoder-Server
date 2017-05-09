<?php
/**
 * Created by PhpStorm.
 * User: wangju
 * Date: 2017/5/7
 * Time: 上午12:35
 */

namespace App\Transformers;


use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user'
    ];

    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'body' => $comment->body,
            'create_at' =>$comment->created_at->toDateTimeString(),
            'votesCount' => $comment->votesCount,
            'isVoted' => $comment->isVoted
        ];
    }

    public function includeUser(Comment $comment)
    {

        $user = $comment->user;
        return $this->item($user, new UserTransformer);
    }

}