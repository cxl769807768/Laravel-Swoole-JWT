<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;



class RegisterController extends AuthController
{
    public function create(RegisterRequest $request)
    {

        // 创建用户
        $result = User::create([
            'mobile' => $request->input('mobile'),
            'name' => 123,
            'password' => Hash::make($request->input('password')),
        ]);
        if ($result) {
            $userInfo = User::where('mobile','=',$request->input('mobile'))->first();
            $token = auth('api')->login($userInfo);
            if (!$token) {
                return response()->json(['code'=>500,'message' => 'token生成失败'], 401);
            }
            return $this->respondWithToken($token);
        } else {
            return response()->json(['code'=>500,'message' => '创建用户失败']);
        }
    }

}
