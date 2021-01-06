<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductsController extends CommonController
{
    protected $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->middleware('jwt.auth')->except(['index','show']);
    }
    public function index(Request $request)
    {
        $where['status'] = 1;
        $pageSize = $request->input('pageSize');
        $tid = $request->input('tid');
        $page = $request->input('page');
        $where['tid'] = (int)$tid ?? null;
        $where['pageSize'] = (int)$pageSize ?? 10;
        $where['page'] = (int)$page ?? 1;
        $data = $this->product->getList($where);
        return response()->json([
            'code'=>200,
            'msg'=>"获取成功",
            'data'=>$data
        ]);
    }
    public function show(Request $request)
    {

        $data = $this->product->where('id', $request->input('id'))->first();
        return response()->json([
            'code'=>200,
            'msg'=>"获取成功",
            'data'=>$data
        ]);
    }
}
