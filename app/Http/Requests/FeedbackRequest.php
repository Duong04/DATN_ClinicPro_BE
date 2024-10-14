<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FeedbackRequest extends FormRequest
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
        return [
            'rating' => 'required|max:5|min:0|integer',
            'content' => 'required',
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:examination_packages,id'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống!',
            'max' => ':attribute không được lớn hơn :max ký tự!',
            'min' => ':attribute không được nhỏ hơn :min ký tự!',
            'exists' => 'Giá trị của :attribute không tồn tại!',
            'integer' => ':attribute phải la số'
        ];
    }

    public function attributes(): array
    {
        return [
            'rating' => 'Đánh giá',
            'content' => 'Nội dung',
            'user_id' => 'ID người dùng',
            'package_id' => 'ID gói khám'
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
