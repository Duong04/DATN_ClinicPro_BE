<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'fullname' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',         
                'regex:/[@$!%*#?&]/'   
            ],
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => ':attribute là bắt buộc!',
            'unique' => ':attribute này đã tồn tại!',
            'min' => ':attribute không nhược nhỏ hơn :min kí tự!',
            'max' => ':attribute không được lớn hơn :max kí tự',
            'email' => 'Vui lòng nhập đúng định dạng email!',
            'password.regex' => ':attribute phải chứa ít nhất một ký tự in hoa và một ký tự đặc biệt!'
        ];
    }

    public function attributes() {
        return [
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'fullname' => 'Họ và tên',
        ];
    }
}
