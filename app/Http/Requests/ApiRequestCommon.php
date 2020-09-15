<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiRequestCommon extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        foreach((new ValidationException($validator))->errors() as $key =>$val){
            if(is_array($val)){
                $values[] = array_values($val);
            }

        }
        throw new HttpResponseException(response()->json([
            'code'=>400,
            'msg' => $values[0][0]
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));

    }
}
