<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiRequestCommon extends FormRequest
{
    /**
     * @param Validator $validator
     * 修改验证不通过的错误返回格式
     */
    protected function failedValidation(Validator $validator)
    {
        foreach((new ValidationException($validator))->errors() as $key =>$val){
            if(is_array($val)){
                $values[] = array_values($val);
            }

        }
        throw new HttpResponseException(response()->json([
            'code'=>500,
            'msg' => $values[0][0]
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));

    }
}
