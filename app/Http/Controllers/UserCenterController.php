<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCenterController extends Controller
{
    //返回用户列表
    public function index(){
        //3个用户为一页
        $users = User::paginate(15);
        return $users;
    }
    //返回单一用户信息
    public function show(User $user,Request $request){
        return response()->json([
            'code'=>200,
            'msg'=>"获取成功",
            'data'=>Auth::guard('api')->user()
        ]);

    }

}
