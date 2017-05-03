<?php

namespace App\Http\Controllers\Api;

use App\Models\Gallery;
use App\Transformers\GalleryTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class GalleriesController extends ApiController
{
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
     *    "code": 200,
     *    "message": "没有找到该记录"
     *   }
     */
    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (is_null($gallery)){
            return $this->respondWithError('没有找到该记录');
        }

        return $this->respondWithItem($gallery,new  GalleryTransformer);

    }
}
