<?php

namespace App\Http\Controllers\Api;

use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('jwt.auth');
    }

    public function show(User $user)
    {

        return Auth::user();
    }

    public function doFollow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required|exists:users'
        ],[
            'id.required' => '用户的id不能为空',
            'id.exists' => '用户不存在'
        ]);
        if ($validator->fails()) {
            return $this->respondWithFailedValidation($validator);
        }

        $user_id = $request->id;

        if (Auth::id() == $user_id){
            return $this->respondWithError('自己不能关注自己');
        }

        if (Auth::user()->isFollowing($user_id)){
            Auth::user()->unfollow($user_id);
            return $this->respondWithMessage('取消关注成功');
        }else{
            Auth::user()->follow($user_id);

            // 添加通知事件
            // todo

            return $this->respondWithMessage('关注成功');
        }

    }

    public function following(User $user)
    {
        $users = $user->followings()->orderBy('id', 'desc')->paginate(15);

        return $this->respondWithPaginator($users,new UserTransformer);
    }

    public function followers(User $user)
    {
        $users = $user->followers()->orderBy('id', 'desc')->paginate(15);
        return $this->respondWithPaginator($users,new UserTransformer);
    }







}
