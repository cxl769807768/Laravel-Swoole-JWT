<?php

namespace App\Http\Requests;

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
            'name' => 'required|min:6',
//            'email'=>'required|email|unique:users',
            'mobile'=>[
                'required',
                'regex:/^1\d{10}$/',
                'unique:users'
            ],
            'password'=>'required|min:6|max:16',//密码最低字段长度6
            //确认密码字段的格式必须是 密码字段_confirmation
            'password_confirmation' => 'required',
            'captcha' => 'required|captcha',

        ];
    }
    public function messages()
    {
        return [

            'name.required'  => '名字不能为空',
            'name.min:6'  => '名字格式不正确',
            'mobile.required'  => '手机号不能为空',
            'mobile.regex'  => '手机号格式不正确',
            'mobile.unique:users'  => '该用户已存在',
//            'email.required'  => '邮箱不能为空',
//            'email.email'  => '邮箱格式不正确',
//            'email.unique:users'  => '邮箱不能重复',
            'password.required'  => '密码不能为空',
            'password.min:6' => '密码格式不正确',
            'password.max:16' => '密码格式不正确',
            'password_confirmation.required' => '确认密码不能为空！',
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '请输入正确的验证码',

        ];
    }

}
