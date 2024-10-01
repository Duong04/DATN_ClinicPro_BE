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
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'dosage' => 'required'
        ];
        if ($this->method() == 'PUT') {
            $rules['patient_id'] = 'nullable';
            $rules['doctor_id'] = 'nullable';
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống!',
            'exists' => 'Giá trị của :attribute không tồn tại!',
        ];
    }

    public function attributes(): array
    {
        return [
            'doctor_id' => 'Id bác sĩ',
            'patient_id' => 'Id bện nhân',
            'name' => 'Tên đơn thuốc',
            'description' => 'Mô tả',
            'dosage' => 'Liều lượng'
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
