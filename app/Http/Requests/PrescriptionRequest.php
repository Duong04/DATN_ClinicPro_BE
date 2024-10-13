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
            'instructions' => 'required',
            'frequency' => 'required',
            'dosage' => 'required',
            'duration' => 'required|integer|min:1'
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
            "min" => ":attribute không được nhỏ hơn :min ký tự! ",
            'integer' => ':attribute phải la số'
        ];
    }

    public function attributes(): array
    {
        return [
            'doctor_id' => 'Id bác sĩ',
            'patient_id' => 'Id bện nhân',
            'name' => 'Tên đơn thuốc',
            'instructions' => 'Hướng dẫn sử dụng',
            'dosage' => 'Liều lượng',
            'frequency' => 'Tần suất',
            'duration' => 'Thời gian sử dụng'
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
