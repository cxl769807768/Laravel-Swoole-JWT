<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request,User $user)
    {
        $validatedData = $request->validated();
        print_r($validatedData);exit;

        $user = User::create([
            'name'      => $request->name,
            'mobile'     => $request->mobile,
            'password'  => $user->setPasswordAttribute($request->password),
        ]);

        return response()->json([
            'code'=>200,
            'msg'=>"注册成功",
            'data'=>[]
        ]);
    }
    public function create(){
        echo 234;
    }
}
