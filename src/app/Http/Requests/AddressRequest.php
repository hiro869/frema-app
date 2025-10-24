<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'zip' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address1' => ['required','string','max:255'],
            'address2' => ['nullable','string','max:255'],
        ];
    }
    public function messages(): array {
        return [
            'zip.required' => '郵便番号を入力してください。',
            'zip.regex' => '郵便番号はハイフンありの8文字（例：123-4567）で入力してください。',
            'address1.required' => '住所を入力してください。'
        ];
    }
}
