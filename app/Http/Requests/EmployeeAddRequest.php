<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\Base;
use Illuminate\Contracts\Validation\Validator;

class EmployeeAddRequest extends FormRequest
{
    use Base;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // "name" => "required",
            // "email" => "required|email|unique:users",
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            "password" => "required|min:6",
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Base::validation($validator));
    }

    public function messages()
    {
        return [
            "usernamename.required" => "Name is required",
            "email.required" => "Email is required",
            "email.email" => "Email is invalid",
            "email.unique" => "Email is already taken",
            "password.required" => "Password is required"
        ];
    }
}
