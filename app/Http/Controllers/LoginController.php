<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends CommonController
{
    public function login(Request $request){
       print_r($request->path());
    }
    public function postLogin(Request $request)
    {
        if (Auth::attempt(array('email' => $request->input('username'), 'password' => $request->input('password'))))
        {
            return Redirect::intended('/');
        }
    }
    public function save(Request $request){
        //jwt token
        $credentials = $request->only('name', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['result'=>'failed']);
        }
        return $this->responseWithToken($token);
    }
    public function out(Request $request)
    {
        $this->logout($request);
    }
}
