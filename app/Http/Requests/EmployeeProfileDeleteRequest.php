<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Base;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeProfileDeleteRequest extends FormRequest
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
    public function rules()
    {
        return [
            'delete_reason' => 'required|string'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Base::validation($validator));
    }

    public function message()
    {
        return [
            //
        ];
    }
}
