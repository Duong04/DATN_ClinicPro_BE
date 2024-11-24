<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'name' => 'required|unique:departments,name',
            'manager_id' => 'nullable|exists:users,id',
            'description' => 'nullable',
            'users' => 'nullable|array',
        ];

        if ($this->method() == 'PUT') {
            $id = $this->route('id');
            $rules['name'] = 'required|unique:departments,name,'.$id;
            $rules['users_delete'] = 'nullable|array';
        }

        return $rules;
    }

    public function messages() {
        return [
            'required' => ':attribute là bắt buộc!',
            'unique' => ':attribute này đã tồn tại!',
            'exists' => 'Giá trị của :attribute không tồn tại!',
            'array' => ':attribute phải là 1 array'
        ];
    }

    public function attributes() {
        return [
            'name' => 'Tên',
            'description' => 'Mô tả',
            'users' => 'Danh sách người dùng'
        ];
    }
}
