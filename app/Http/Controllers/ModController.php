<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModController extends Controller
{
    public function index(Request $request){
        return response()->json([
            'code'=>200,
            'msg'=>"获取成功",
            'data'=>DB::table('mod_type')->get()
        ]);

    }
}
