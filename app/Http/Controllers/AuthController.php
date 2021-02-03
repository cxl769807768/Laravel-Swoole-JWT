<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Captcha;

/**
 * Class AuthController
 * @package App\Http\Controllers Token控制器
 */
class AuthController extends CommonController
{
    protected $guard = 'api';//如果不设置成员变量熟悉，使用的时候请使用助手函数，例如：$token = auth('api')->tokenById($uid);

    /**
     * Create a new AuthController instance.
     * @return void
     * a) 基于账密参数
    这就是刚刚说的哪一种，贴出具体代码。

    // 使用辅助函数
    $credentials = request(['email', 'password']);
    $token = auth()->attempt($credentials)

    // 使用 Facade
    $credentials = $request->only('email', 'password');
    $token = JWTAuth::attempt($credentials);
    b) 基于 users 模型返回的实例
    // 使用辅助函数
    $user = User::first();
    $token = auth()->login($user);

    // 使用 Facade
    $user = User::first();
    $token = JWTAuth::fromUser($credentials);
    c) 基于 users 模型中的主键 id
    // 使用辅助函数
    $token = auth()->tokenById(1);

    // 使用 Facade
    源码中没找到

     */
    public function __construct()
    {

        $this->middleware('jwt.auth', ['except' => ['login','register']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        $where['mobile'] = $mobile ?? null;
        $userInfo = User::where('mobile','=',$mobile)->first();
        if (!$userInfo || !Hash::check($password, $userInfo->password)) {
            response()->json(['code'=>500,'msg' => '账号或密码错误'], 401);
        }
        //生成token
        $token = auth('api')->login($userInfo);
        if (!$token) {
            return response()->json(['code'=>500,'message' => 'token生成失败'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logOut()
    {
        auth('api')->logout();

        return response()->json(['code'=>200,'message' => '退出成功！']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * 获取用户信息
     */
    public function getUser()
    {
        return $this->json(200,'',auth('api')->user());
    }
    public function register(RegisterRequest $request)
    {

        // 创建用户
        $result =  User::create([
            'avatar' => $request->input('avatar') ?? '',
            'nickname' => $request->input('nickname') ?? '',
            'sign' => $request->input('sign') ?? '',
            'mobile' => $request->input('mobile'),
            'name' =>  $request->input('name') ? $request->input('name') : substr_replace($request->input('mobile'),'****',3,4),
            'password' => Hash::make($request->input('password')),
        ]);

        if (!empty($result)) {

            //生成token
            $userInfo = User::where('mobile','=',$request->input('mobile'))->first();
            $this->userToGrouop($userInfo['id']);
            //生成token
            $token = auth('api')->login($userInfo);
            if (!$token) {
                return response()->json(['code'=>500,'message' => 'token生成失败'], 401);
            }
            return $this->respondWithToken($token);

        } else {
            return response()->json(['message' => '创建用户失败']);
        }

    }
    public function userToGrouop(int $user_id){

        //将用户添加到所有人都在群
        DB::table('c_group_member')->insert([
            'user_id' => $user_id,
            'group_id' => 10008
        ]);

    }
    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'code'=>200,
            'data'=>[
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL(),
                'userInfo'=>auth('api')->user()
            ]
        ]);
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard($this->guard);
    }
}
