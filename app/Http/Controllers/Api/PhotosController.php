<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Image\PhotosManager;
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
            $errors = $validator->errors()->first();
            return $this->setStatusCode(400)->respondWithError($errors);
        }

        $gallery = Gallery::find($gallery);

        if (is_null($gallery)){
            return $this->setStatusCode(404)->respondWithError('该图集找不到');
        }

        $file = $request->file('photo');

        $photo = (new PhotosManager($file))->store();

        $gallery->photos()->save($photo);
        

        return $gallery->load('photos');


    }


}
