<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {  //获取到用户数据，并赋值给$user
                return response()->json([
                    'code' => 1004,
                    'msg' => '无此用户'

                ], 404);
            }
            return $next($request);

        } catch (TokenExpiredException $e) {

            return response()->json([
                'code' => 1003,
                'msg' => 'token 过期' , //token已过期
            ]);

        } catch (TokenInvalidException $e) {

            return response()->json([
                'code' => 1002,
                'msg' => 'token 无效',  //token无效
            ]);

        } catch (JWTException $e) {

            return response()->json([
                'code' => 1001,
                'msg' => '缺少token' , //token为空
            ]);

        }
    }
}
