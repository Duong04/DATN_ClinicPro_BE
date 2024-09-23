<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
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
            'name' => 'required|unique:actions,name',
            'value' => 'required|unique:actions,value',
            'permissions' => 'nullable|array',
        ];

        if ($this->method() == 'PUT') {
            $id = $this->route('id');
            $rules['name'] = 'nullable|unique:actions,name,' . $id;
            $rules['value'] = 'nullable|unique:actions,value,' . $id;
        }

        return $rules;
    }


}
