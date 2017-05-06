<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Image\PhotosManager;
use App\Models\Photo;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Models\Gallery;
use Illuminate\Http\Request;

class PhotosController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth.api');
    }



    /**
     * @api {post} /v1/galleries/:id/photos 在时光集添加照片
     * @apiGroup Gallery
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission form-data
     * @apiParam {String} photo 时光集的照片
     * @apiParam {String} [description] 照片的描述
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
     *    "message": "上传图片不能为空"
     *   }
     */
    public function store(Request $request,$gallery)
    {

        //验证数据
        $validator = Validator::make($request->all(), [
            'photo'     => 'required|file|image'
        ],[
            'photo.required' => '上传图片不能为空',
            'photo.file' => '上传文件失败',
            'photo.image' => '上传文件必须为图片格式'
        ]);
        if ($validator->fails()) {

            return $this->respondWithFailedValidation($validator);
        }

        $gallery = Gallery::find($gallery);

        if (is_null($gallery)){
            return $this->responseNotFond('没有找到该图集');
        }

        $file = $request->file('photo');

        $photo = (new PhotosManager($file))->store();

        $photo->description = $request->get('description');

        $gallery->photos()->save($photo);

        return $gallery->load('photos');

    }



    // 上传文件为空的问题
    //https://laracasts.com/discuss/channels/general-discussion/i-can-not-get-data-via-requests-put-patch
    /**
     * @api {put} /v1/photos/:id 更新照片和描述信息
     * @apiGroup Photo
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission form-data  [?_method=put]  无法上传的话在url影藏域添加此参数
     * @apiParam {String} [photo] 时光集的照片
     * @apiParam {String} [description] 照片的描述
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "status": "success",
     *       "code": 200,
     *       "message": "修改成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *   {
     *    "status": "error",
     *    "code": 400,
     *    "message": "上传文件必须为图片格式"
     *   }
     */
    public function update(Request $request,Photo $photo)
    {
        //验证数据
        $validator = Validator::make($request->all(), [
            'photo'     => 'file|image'
        ],[
            'photo.file' => '上传文件失败',
            'photo.image' => '上传文件必须为图片格式'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return $this->setStatusCode(400)->respondWithError($errors);
        }

        $this->authorize('update',$photo);

        if ($request->hasFile('photo')){
            $file = $request->file('photo');

            $new_photo = (new PhotosManager($file))->store();

            $photo->update($new_photo->toArray());
        }

        $data = array_filter([
            'description' => $request->get('description')
        ]);

        $photo->update($data);
        $photo->save();

        return $this->respondWithMessage('图片信息更新成功');

    }

    /**
     * @api {delete} /v1/photos/:id 删除照片
     * @apiGroup Photo
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiPermission 只能删除用户自己图集的照片
     * @apiParam {int} id 要删除照片的id
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
     *    "code": 400,
     *    "message": "没有找到改图片"
     *   }
     */
    public function destroy(Request $request,$photo)
    {
        $photo = Photo::find($photo);
        if (is_null($photo)){
            return $this->responseNotFond('没有找到该图片');
        }

        $this->authorize('destroy',$photo);

        $photo->delete();
        return $this->respondWithMessage('图片删除成功');

    }


}
