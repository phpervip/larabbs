<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           // 'name'=>'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
            'name'=>'required|between:3,25|regex:/[\w\x{4e00}-\x{9fa5}]{2,25}/u|unique:users,name',
            'password'=>'required|string|min:6',
            'verification_key'=>'required|string',
            'verification_code'=>'required|string',
        ];

    }

    public function attributes()
    {
        return [
            'verification_key'=>'短信验证码 key',
            'verification_code'=>'短信验证码',
        ];
    }

}
