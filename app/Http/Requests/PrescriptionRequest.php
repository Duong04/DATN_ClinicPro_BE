<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PrescriptionRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:patients,id',
            'description' => 'nullable|string',
            'medications' => 'required|array',
            'name' => 'required',
            'medical_histories_id' => 'required|exists:medical_histories,id',

            'medications.*.medication_id' => 'required',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.instructions' => 'nullable|string',
            'medications.*.duration' => 'required|integer|min:1',
        ];
        if ($this->method() == 'PUT') {
            $rules['patient_id'] = 'nullable|exists:patients,id';
            $rules['user_id'] = 'nullable|exists:users,id';
            $rules['medical_histories_id'] = 'nullable|exists:medical_histories,id';
            $rules['medications.*.id'] = 'required|exists:prescription_infos,id';
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống!',
            'exists' => 'Giá trị của :attribute không tồn tại!',
            'min' => ':attribute không được nhỏ hơn :min!',
            'integer' => ':attribute phải là số',
            'string' => ':attribute phải là chuỗi ký tự',
            'array' => ':attribute phải là một mảng',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'Id Bác sĩ',
            'patient_id' => 'Id bệnh nhân',
            'instructions' => 'Hướng dẫn sử dụng',
            'duration' => 'Thời gian sử dụng',
            'medication_id' => 'ID thuốc',
            'quantity' => 'số lượng',
            'medical_histories_id' => 'ID bệnh án'
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
