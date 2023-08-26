<?php

namespace App\Http\Requests\Authentication;

use App\Traits\Base;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyOTP extends FormRequest
{
    use Base;
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'otp' => 'required|numeric',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Base::validation($validator));
    }

    public function messages()
    {
        return [
            'otp.required' => 'OTP is required',
            'otp.numeric' => 'OTP is invalid',
        ];
    }
}
