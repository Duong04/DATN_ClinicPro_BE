<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatAIRequest extends FormRequest
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
            'prompt' => 'required|string',
            'id' => 'nullable|exists:conversations,id'
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => ':attribute là bắt buộc!',
            'exists' => 'Giá trị của :attribute không tồn tại!',
        ];
    }

    public function attributes() {
        return [
            'prompt' => 'Prompt',
        ];
    }
}
