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
        $this->middleware('jwt.auth');
    }

    /**
     *
     * 存储图集的照片
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
     * 更新图片和描述信息
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
