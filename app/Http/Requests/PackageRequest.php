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
            'category_id' => "required|exists:category_packages,id",
            'specialty_id' => 'required|exists:specialties,id',
            'image' => 'required|string'
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống!',
            'max' => ':attribute không được lớn hơn :max ký tự!',
            'unique' => ':attribute này đã tồn tại!',
            'exists' => 'Giá trị của :attribute không tồn tại!',
            'string' => ':attribute này phải là 1 string!',
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
            'image' => 'Hình ảnh',
            "category_id" => 'ID danh mục',
            'specialty_id' => 'ID chuyên khoa'
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
