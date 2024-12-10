<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalHistoryRequest extends FormRequest
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
            'patient_id' => 'required|exists:patients,id',
            'description' => 'nullable|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'indication' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'files.*.file' => 'nullable|string',
            'files.*.description' => 'nullable',
            'files.*.id' => 'nullable',
            'file_deletes' => 'nullable|array',
            'services.*.id' => 'required|exists:services,id',
            'services' => 'nullable|array',
            'service_deletes' => 'nullable|array',
        ];

        if ($this->method() == 'PUT') {
            $id = $this->route('id');
            if ($this->has('patient_id')) {
                $rules['patient_id'] = 'required|exists:patients,id';
            } else {
                $rules['patient_id'] = 'nullable|exists:patients,id';
            }

            if ($this->has('diagnosis')) {
                $rules['diagnosis'] = 'required|string';
            } else {
                $rules['diagnosis'] = 'nullable|string';
            }

            if ($this->has('treatment')) {
                $rules['treatment'] = 'required|string';
            } else {
                $rules['treatment'] = 'nullable|string';
            }

            if ($this->has('user_id')) {
                $rules['user_id'] = 'required|exists:users,id';
            } else {
                $rules['user_id'] = 'nullable|exists:users,id';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute là bắt buộc!',
            'string' => ':attribute này phải là 1 string',
            'exists' => 'Giá trị của :attribute không tồn tại!',
            'file' => ':attribute phải là 1 tệp'
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'Mô tả',
            'diagnosis' => 'Chẩn đoán',
            'treatment' => 'Điều trị',
            'files.*.file' => 'Hồ sơ',
            'files.*.description' => 'Mô tả',
            'services' => 'Dịch vụ',
            'services.*.id' => 'Dịch vụ'
        ];
    }
}
