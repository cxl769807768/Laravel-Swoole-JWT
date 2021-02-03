<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class UserController extends CommonController
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->middleware('jwt.auth')->except(['index','show']);
    }
    public function index(Request $request)
    {
        $where['status'] = 1;
        $pageSize = $request->input('pageSize');
        $page = $request->input('page');
        $where['pageSize'] = (int)$pageSize ?? 10;
        $where['page'] = (int)$page ?? 1;
        $data = $this->user->getList($where)->toArray();

        return response()->json([
            'code'=>!empty($data['data']) ? 200 : 0,
            'msg'=>"获取成功",
            'data'=>$data
        ]);
    }
    public function show(Request $request)
    {

        $data = $this->user->where('id', $request->input('id'))->first();
        return response()->json([
            'code'=>200,
            'msg'=>"获取成功",
            'data'=>$data
        ]);
    }
    public function create(Request $request)
    {

    }
}
