<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'status' => 'nullable|in:active,inactive',
            'user_info.avatar' => 'nullable|string',
            'doctor.specialty_id' => 'nullable|exists:specialties,id',
            'user_info.fullname' => 'required',
            'user_info.address' => 'nullable',
            'user_info.phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'user_info.gender' => 'nullable|in:male,female,other',
            'user_info.dob' => 'nullable|date|before_or_equal:today',
            'user_info.department_id' => 'nullable|exists:departments,id',
            'user_info.identity_card.type_name' => 'nullable',
            'user_info.identity_card.identity_card_number' => 'nullable'
        ];

        if ($this->method() === 'PUT') {
            $id = $this->route('id');
            $rules['email'] = 'required|email|unique:users,email,'.$id;
        }

        return $rules;
    }

    public function messages() {
        return [
            'required' => ':attribute là bắt buộc!',
            'email' => 'Vui lòng nhập đúng định dạng email!',
            'unique' => ':attribute này đã tồn tại!',
            'min' => ':attribute không được nhỏ hơn :min kí tự!',
            'image' => ':attribute phải là 1 ảnh!' ,
            'user_info.gender.in' => ':attribute phải là một trong các giá trị: male, female, other!', 
            'status.in' => 'Trạng thái phải là một trong các giá trị: active, inactive!', 
            'date' => 'Vui lòng nhập đúng định dạng ngày tháng!',
            'exists' => 'Giá trị của :attribute không tồn tại!',
            'regex' => ':attribute không đúng định dạng!',
            'dob.before_or_equal' => ':attribute không được lớn hơn ngày hiện tại!',
        ];
    }

    public function attributes() {
        return [
            'status' => 'Trạng thái',
            'user_info.fullname' => 'Họ và tên',
            'user_info.address' => 'Địa chỉ',
            'user_info.phone_number' => 'Số điện thoại',
            'user_info.gender' => 'Giới tính',
            'user_info.dob' => 'Ngày sinh',
            'user_info.department_id' => 'Phòng ban',
            'user_info.identity_card.type_name' => 'Loại thẻ',
            'user_info.identity_card.identity_card_number' => 'Số thẻ',
            'doctor.description' => 'Mô tả',
            'doctor.specialty' => 'Chuyên khoa',
        ];
    }
}
