<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'service_name' => 'required|string|unique:services,service_name',
            'description' => 'nullable|string',
            'price' => 'required|numeric'
        ];

        if ($this->method() == 'PUT') {
            $id = $this->route('id');
            if ($this->has('service_name')) {
                $rules['service_name'] = 'required|unique:services,service_name,' . $id;
            } else {
                $rules['service_name'] = 'nullable|unique:services,service_name,' . $id;
            }

            if ($this->has('price')) {
                $rules['price'] = 'required|numeric';
            } else {
                $rules['price'] = 'nullable|numeric';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute là bắt buộc.',
            'string' => ':attribute phải là một chuỗi ký tự hợp lệ.',
            'unique' => ':attribute phải là duy nhất.',
            'numeric' => ':attribute phải là một giá trị số hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'service_name' => 'Tên dịch vụ',
            'description' => 'Mô tả',
            'price' => 'Giá dịch vụ',
        ];
    }
}
