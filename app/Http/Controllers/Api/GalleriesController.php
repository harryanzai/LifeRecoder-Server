<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Gallery;
use App\Transformers\GalleryTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class GalleriesController extends ApiController
{

    public function __construct()
    {

        parent::__construct();
        $this->middleware('auth.api')->except([
            'index',
            'show'
        ]);
    }

    /**
     * @api {get} /v1/galleries 获取所有的图片集
     * @apiGroup Gallery
     * @apiPermission none
     * @apiParam {String} [limit] 每页默认返回的数量
     * @apiParam {String} [page] 不传默认返回第1页
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "data": [
     *           {
     *               "id": 63,
     *               "title": "Qui recusandae voluptatum quia necessitatibus.",
     *               "content": "Et rem provident est quaerat in voluptatem.",
     *               "user": {
     *                   "id": 86,
     *                   "nickname": "隋帅",
     *                   "email": "adipisci.cum@example.net",
     *                   "avatar": "http://lorempixel.com/400/400/?60392",
     *                   "gender": "未设置"
     *               },
     *               "photos": [
     *               {
     *                   "id": 86,
     *                   "name": "戴斌",
     *                   "path": "http://lorempixel.com/640/480/?93717",
     *                   "thumbnail_path": "http://lorempixel.com/400/400/?33995",
     *                   "description": "Rerum autem non eum aspernatur fugiat quasi qui."
     *               }
     *               ]
     *           }],
     *           "pagination": {
     *           "total": 120,
     *           "count": 1,
     *           "per_page": 1,
     *           "current_page": 3,
     *           "total_pages": 120
     *           }
     *       }
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 15;
        $galleries = Gallery::latest()->paginate($limit);

        return $this->respondWithPaginator($galleries,new GalleryTransformer);
    }

    /**
     * @api {get} /v1/gallery/:id 获取单个图片集
     * @apiGroup Gallery
     * @apiPermission none
     * @apiParam {String} id 所获取图片集的id
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "data":
     *           {
     *               "id": 63,
     *               "title": "Qui recusandae voluptatum quia necessitatibus.",
     *               "content": "Et rem provident est quaerat in voluptatem.",
     *               "user": {
     *                   "id": 86,
     *                   "nickname": "隋帅",
     *                   "email": "adipisci.cum@example.net",
     *                   "avatar": "http://lorempixel.com/400/400/?60392",
     *                   "gender": "未设置"
     *               },
     *               "photos": [
     *               {
     *                   "id": 86,
     *                   "name": "戴斌",
     *                   "path": "http://lorempixel.com/640/480/?93717",
     *                   "thumbnail_path": "http://lorempixel.com/400/400/?33995",
     *                   "description": "Rerum autem non eum aspernatur fugiat quasi qui."
     *               }
     *               ]
     *           },
     *
     *       }
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *    "status": "error",
     *    "code": 404,
     *    "message": "没有找到该记录"
     *   }
     */
    public function show(Gallery $gallery)
    {
        return $this->respondWithItem($gallery,new  GalleryTransformer);
    }


    /**
     *
     * 建立时光图片集
     */
    public function store(Request $request)
    {
        //验证数据

        $validator = Validator::make($request->all(), [
            'title'     => 'required|min:3',
        ],[
            'title.required' => '标题不能为空',
            'title.min' => '标题不能少于3个字符'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return $this->respondWithError($errors);
        }

        $user = Auth::user();
        $user->galleries()->create([
            'title' => $request->title,
            'content' => $request->get('content'),
            'mood_id' => $request->get('mood_id')
        ]);

        return $this->respondWithSuccess('添加成功');

    }


    /**
     *  更新图片集
     */
    public function update(Request $request,Gallery $gallery)
    {
        $this->authorize('update',$gallery);

        $data = array_filter([
            'title' => $request->title,
            'content' => $request->get('content'),
        ]);

        $gallery->update($data);
        $gallery->save();
        return $this->respondWithMessage('修改成功');
    }


    /**
     *
     * 删除图片集
     */
    public function destroy(Gallery $gallery)
    {





    }


}
