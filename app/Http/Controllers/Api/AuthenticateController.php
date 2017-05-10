<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Toplan\Sms\Facades\SmsManager;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth.api')->except([
            'sendCheckCode',
            'registerUser',
            'loginUser',
            'token'
        ]);

        $this->middleware('jwt.refresh')->only([
            'token'
        ]);
    }

    /**
     * @api {post} /v1/seedCode 获取手机验证码
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

    /**
     * @api {post} /v1/register app注册
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {String} mobile 用户的手机号
     * @apiParam {String} verifyCode 请求的验证码
     * @apiParam {String} password 用户的密码
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *           "status": "success",
     *           "code": 200,
     *           "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQsImlzcyI6Imh0dHA6XC9cL2xpZmVyZWNvZGVyLmRldlwvYXBpXC92MVwvbG9naW4uanNvbiIsImlhdCI6MTQ5MzY0NTgyMCwiZXhwIjoxNDkzNjQ5NDIwLCJuYmYiOjE0OTM2NDU4MjAsImp0aSI6Ijc5VGxIYzNubVVGMkJmczMifQ.usSRe47EgnIqgZ2UXAFb2RdF-u1N6Kn9ATRpWHDH0sU",
     *       }
     * @apiErrorExample {json} Error-Response:
     *     {
     *           "status": "error",
     *           "code": 400,
     *           "message": "用户名已经存在"
     *      }
     */
    public function registerUser(Request $request)
    {
        // 添加用户请求的标识
        $request->request->add([
            'access_token' => $request->mobile
        ]);
        //验证数据
        $validator = Validator::make($request->all(), [
            'mobile'     => 'required|confirm_mobile_not_change|confirm_rule:mobile_required|unique:users',
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

        User::create([
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password)
        ]);

        return $this->loginUser($request);
    }


    /**
     * @api {post} /v1/login app登录
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {String} mobile 用户的手机号
     * @apiParam {String} password 用户的密码
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *           "status": "success",
     *           "code": 200,
     *           "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQsImlzcyI6Imh0dHA6XC9cL2xpZmVyZWNvZGVyLmRldlwvYXBpXC92MVwvbG9naW4uanNvbiIsImlhdCI6MTQ5MzY0NTgyMCwiZXhwIjoxNDkzNjQ5NDIwLCJuYmYiOjE0OTM2NDU4MjAsImp0aSI6Ijc5VGxIYzNubVVGMkJmczMifQ.usSRe47EgnIqgZ2UXAFb2RdF-u1N6Kn9ATRpWHDH0sU",
     *       }
     * @apiErrorExample {json} Error-Response:
     *     {
     *           "status": "error",
     *           "code": 400,
     *           "message": "用户名或密码错误"
     *      }
     */
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
            return $this->setStatusCode(400)->respondWithError($errors);
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


    /**
     * @api {post} /v1/logout app退出登录
     * @apiGroup Auth
     * @apiPermission none
     * @apiHeaderExample {json} Header-Example:
     *    {
     *       "Authorization" : "Bearer {token}"
     *    }
     * @apiVersion 0.0.0
     * @apiSuccessExample {json} Success-Response:
     *     {
     *           "status": "success",
     *           "code": 200,
     *           "message": "退出登录成功",
     *       }
     * @apiErrorExample {json} Error-Response:
     *     {
     *           "error": "token_invalid"
     *      }
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();
        } catch (TokenBlacklistedException $e) {
            return $this->failure(trans('jwt.the_token_has_been_blacklisted'), 500);
        } catch (JWTException $e) {
            // 忽略该异常（Authorization为空时会发生）
        }
        return $this->respondWithMessage('退出登录成功');
    }


    /**
     *
     * 刷新token
     */
    public function token()
    {
        $currentToken = JWTAuth::getToken();


        return $currentToken;


    }

}
