<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends ApiRequestCommon
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',//名字最低字段长度6
            'mobile'=>'required|unique:users',//email格式，在users表里不可重复
            'password'=>'required|min:6|confirmed',//密码最低字段长度6
            //确认密码字段的格式必须是 密码字段_confirmation
            'password_confirmation' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'name.required' => '用户名不能为空',
            'mobile.required'  => '手机号不能为空',
//            'mobile.mobile'  => '手机号格式不正确',
            'mobile.unique:users'  => '该用户已存在',
            'password.required'  => '密码不能为空',
            'password.min:6' => '密码不正确',
            'password_confirmation.required' => '确认密码不能为空！',

        ];
    }

}
