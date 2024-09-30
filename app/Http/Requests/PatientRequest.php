<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'insurance_number' => 'nullable',
            'status' => 'nullable|in:active,inactive,deceased,transferred',
            'user_info.fullname' => 'required|string',
            'user_info.email' => 'required|email|unique:patient_infos,email|unique:users,email',
            'user_info.phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'user_info.address' => 'nullable|string',
            'user_info.gender' => 'nullable|in:male,female,other',
            'user_info.dob' => 'nullable|date',
            'identity_card.type_name' => 'nullable',
            'identity_card.identity_card_number' => 'nullable'
        ];

        if ($this->method() == 'PUT') {
            $rules['user_info.fullname'] = 'nullable|string';
            $rules['user_info.email'] = 'nullable|email|unique:patient_infos,email|unique:users,email';
        }

        return $rules;
    }

    public function messages() {
        return [
            'required' => ':attribute là bắt buộc!',
            'email' => 'Vui lòng nhập đúng định dạng email!',
            'unique' => ':attribute này đã tồn tại!',
            'min' => ':attribute không được nhỏ hơn :min kí tự!',
            'patient_info.gender.in' => ':attribute phải là một trong các giá trị: male, female, other!', 
            'status.in' => 'Trạng thái phải là một trong các giá trị: active, inactive, deceased, transferred!', 
            'date' => 'Vui lòng nhập đúng định dạng ngày tháng!',
            'regex' => ':attribute không đúng định dạng!',
            'string' => 'attribute phải là 1 là string'
        ];
    }

    public function attributes() {
        return [
            'status' => 'Trạng thái',
            'insurance_number' => 'Bảo hiểm',
            'user_info.fullname' => 'Họ và tên',
            'user_info.address' => 'Địa chỉ',
            'user_info.phone_number' => 'Số điện thoại',
            'user_info.gender' => 'Giới tính',
            'user_info.dob' => 'Ngày sinh',
            'identity_card.type_name' => 'Loại thẻ',
            'identity_card.identity_card_number' => 'Số thẻ',
        ];
    }
}
