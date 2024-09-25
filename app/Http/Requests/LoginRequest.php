<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => ':attribute là bắt buộc!',
            'email' => 'Vui lòng nhập đúng định dạng email!'
        ];
    }

    public function attributes() {
        return [
            'password' => 'Mật khẩu',
        ];
    }
}
