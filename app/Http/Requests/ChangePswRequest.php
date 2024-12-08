<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class ChangePswRequest extends FormRequest
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
            'password' => 'required|string|min:8',
            'new_password' => [
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
            'string' => ':attribute này phải là 1 string!',
            'min' => ':attribute này phải lớn hơn :min ký tự!'
        ];
    }

    public function attributes() {
        return [
            'password' => 'Mật khẩu',
            'new_password' => 'Mật khẩu mới',
            'new_password.regex' => ':attribute phải chứa ít nhất một ký tự in hoa và một ký tự đặc biệt!'
        ];
    }


    public function withValidator($validator)
    {
        $id = auth()->id();

        $validator->after(function ($validator) use ($id) {
            $user = User::find($id);

            if ($user && !Hash::check($this->password, $user->password)) {
                $validator->errors()->add('password', 'Mật khẩu không đúng!');
            }
        });
    }
}
