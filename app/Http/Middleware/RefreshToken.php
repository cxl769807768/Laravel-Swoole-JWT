<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

// 注意，我们要继承的是 jwt 的 BaseMiddleware
class RefreshToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 检查此次请求中是否带有 token，如果没有则抛出异常。
        $this->checkForToken($request);

        // 使用 try 包裹，以捕捉 token 过期所抛出的 TokenExpiredException  异常
        try {
            // 检测用户的登录状态，如果正常则通过
            if ($this->auth->parseToken()->authenticate()) {
                try {
                    //最后十分钟刷新Token
                    $time = Auth::guard('api')->payload()->toArray()['exp'];

                    if(intval($time-time())<600 &&  ($time - time()) > 0 ){
                        // 刷新用户的 token
                        $token = $this->auth->refresh();
                        if($token){
                            $request->headers->set('Authorization', 'Bearer '.$token);
                        }else{
                            return response(['code'=>401,'msg'=>'The token has been blacklisted'],401);
                        }
                        // 在响应头中返回新的 token
                        $respone = $next($request);
                        if(isset($token) && $token){
                            $respone->headers->set('Authorization', 'Bearer '.$token);
                        }
                        return $respone;
                    }
                    Auth::guard('api')->onceUsingId($this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);

                    return $next($request);
                    // 使用一次性登录以保证此次请求的成功
                } catch (JWTException $exception) {
                    // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                    throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
                }

            }
            throw new UnauthorizedHttpException('jwt-auth', '未登录');
        } catch (TokenExpiredException $exception) {
            return response(['code'=>401,'msg'=>'The token has been blacklisted'],401);
        }

    }
}
