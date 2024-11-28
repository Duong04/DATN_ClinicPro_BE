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
            'user_id' => 'required|exists:users,id',
            'files.*.file' => 'nullable|string',
            'files.*.description' => 'nullable',
            'file_deletes' => 'nullable|array',
        ];

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
            'files.*.description' => 'Mô tả'
        ];
    }
}
