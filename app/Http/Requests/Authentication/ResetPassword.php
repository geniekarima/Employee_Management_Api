<?php

namespace App\Http\Requests\Authentication;

use App\Traits\Base;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPassword extends FormRequest
{
    use Base;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Base::validation($validator));
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'token.required' => 'Token is required',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password is not confirmed',
            'password.min' => 'Password is too short',
        ];
    }
}
