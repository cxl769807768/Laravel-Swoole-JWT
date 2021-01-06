<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function  index(){
        $users = DB::table('users')->get();
        print_r($users);
        $aa = "111";
        return view('user.index', ['users' => $users,'aa'=>$aa]);
    }
}
