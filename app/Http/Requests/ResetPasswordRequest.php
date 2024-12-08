<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
    public function rules()
    {
        return [
            'otp' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',         
                'regex:/[@$!%*#?&]/'  
            ]
        ];
    }

    /**
     * Xác định các thông báo lỗi tùy chỉnh.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'otp.required' => 'Mã OTP là bắt buộc!',
            'password.required' => 'Mật khẩu là bắt buộc!',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự!',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một ký tự in hoa và một ký tự đặc biệt!'
        ];
    }
}
