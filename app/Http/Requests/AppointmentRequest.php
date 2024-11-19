<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppointmentRequest extends FormRequest
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
            'fullname' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|min:10|max:11|regex:/^[0-9]+$/',
            'specialty_id' => 'required|exists:specialties,id',
            'address' => 'required',
            'gender' => 'required',
            'dob' => 'required|date',
            'appointment_date' => 'required|date|after:now',
            "package_id" => 'required|exists:examination_packages,id',
            'description' =>  'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống!',
            'min' => ':attribute không được nhỏ hơn :min ký tự!',
            'max' => ':attribute không được lớn hơn :max ký tự!',
            'email' => ':attribute phải là định dạng email!',
            'regex' => ':attribute phải là số hợp lệ!',
            'date' => ':attribute phải là ngày hợp lệ!',
            'appointment_date.after' => ':attribute phải lớn hơn thời gian hiện tại!',
            'exists' => 'Giá trị của :attribute không tồn tại!'
        ];
    }

    public function attributes(): array
    {
        return [
            'fullname' => 'Họ và tên',
            'email' => 'Email',
            'phone_number' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'gender' => 'Giới tính',
            'dob' => 'Ngày sinh',
            'appointment_date' => 'Giờ hẹn',
            "specialty_id" => 'ID chuyên khoa',
            'package_id' => 'ID gói khám',
            "description" =>  'Mô tả'
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
