<?php

namespace App\Http\Requests;

class LoginRequest extends ApiRequestCommon
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
            'mobile'=>[
                'required',
                'regex:/^1\d{10}$/',
            ],
            'password'=>'required|min:6|max:16',//密码最低字段长度6
        ];
    }
    public function messages()
    {
        return [
            'mobile.required'  => '手机号不能为空',
            'mobile.regex'  => '手机号格式不正确',
            'password.required'  => '密码不能为空',
            'password.min:6' => '密码格式不正确',
            'password.max:16' => '密码格式不正确',
        ];
    }
}
