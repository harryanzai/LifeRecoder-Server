<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Toplan\Sms\Facades\SmsManager;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Providers\Auth\AuthInterface;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends ApiController
{


    /**
     * @api {post} /v1/seedCode.json 获取手机验证码
     * @apiDescription 获取手机的验证码，以便用于注册，验证等操作
     * @apiGroup Auth
     * @apiParam {String} mobile 用户用于获取验证码的手机号
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *       {
     *           "status": "success",
     *           "code": 200,
     *           "message": "验证码发送成功"
     *        }
     * @apiErrorExample {json} Error-Response:
     *     {
     *          "status": "error",
     *          "code": 400,
     *          "error": {
     *              "message": "请求错误,请确认手机号是否有误"
     *          }
     *      }
     *
     */
    public function sendCheckCode(Request $request)
    {
        // 添加用户请求的标识
        $request->request->add([
            'access_token' => $request->mobile
        ]);

        $validate = SmsManager::validateFields();

        if ($validate["success"] == false){

            return $this->setStatusCode(400)->respondWithError("请求错误,请确认手机号是否有误");
        }

        $result = SmsManager::requestVerifySms();

        if ($result["success"] == true){
            return $this->respondWithMessage("验证码发送成功");
        }

        return $this->respondInteralError('验证码发送失败,请重试');
    }

    public function registerUser(Request $request)
    {
        // 添加用户请求的标识
        $request->request->add([
            'access_token' => $request->mobile
        ]);
        //验证数据
        $validator = Validator::make($request->all(), [
            'mobile'     => 'required|confirm_mobile_not_change|confirm_rule:mobile_required',
            'verifyCode' => 'required|verify_code',
            'password' => 'required|min:6'
        ],[
            'verifyCode.verify_code' => '验证码不匹配',
            'mobile.confirm_rule' => '确认手机号输入是否有误',
            'mobile.confirm_mobile_not_change' => '手机号或验证不正确'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            SmsManager::forgetState();
            return $this->respondWithError($errors);
        }

        // 验证是否已存在用户
        if (User::where('mobile',$request->mobile)->first()){
            return $this->respondWithError('手机号已注册');
        }

        $user = User::create([
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password)
        ]);

        return $this->loginUser($request);
    }


    public function loginUser(Request $request)
    {

        //验证数据
        $validator = Validator::make($request->all(), [
            'mobile'     => 'required|zh_mobile',
            'password' => 'required|min:6'
        ],[
            'mobile.zh_mobile' => '确认手机号输入是否有误'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return $this->respondWithError($errors);
        }

        $credentials = $request->only('mobile', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->setStatusCode(401)->respondWithError('用户名或密码错误');
            }
        } catch (JWTException $e) {
            return $this->setStatusCode(500)->setStatusCode('服务器错误，不能创建token');
        }

        return $this->respondWithSuccess(compact('token'));
    }



    public function state()
    {
        $state = SmsManager::retrieveState();
        return $state;
    }

}
