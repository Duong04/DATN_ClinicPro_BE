<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PackageRequest extends FormRequest
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
        $id = $this->route('id');

        $rules = [
            'name' => 'required|max:255|unique:examination_packages,name,' . $id,
            'description' => 'required',
            'content' => 'required',
        ];

        if (!$id) {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png,webp';
        } else {
            $rules['image'] = 'nullable|image|mimes:png,jpg,jpeg,webp';
        }


        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống!',
            'max' => ':attribute không được lớn hơn :max ký tự!',
            'unique' => ':attribute này đã tồn tại!',
            'image' => ':attribute phải là một file ảnh hợp lệ!',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Tên gói khám',
            'description' => 'Mô tả',
            'content' => 'Nội dung',
            'image' => 'Hình ảnh'
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
