<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'avatar'    => ['nullable','image','mimes:jpeg,png','max:12288'],
            'name'      => ['required','string','max:20'],
            'zip'       => ['nullable','regex:/^\d{3}-\d{4}$/'], // 例: 123-4567
            'address1'  => ['nullable','string','max:255'],
            'address2'  => ['nullable','string','max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名を入力してください',
            'name.max'      => 'ユーザー名は20文字以内で入力してください',
            'avatar.image'  => 'プロフィール画像は画像ファイルを選択してください',
            'avatar.mimes'  => 'プロフィール画像はjpegまたはpngを選択してください',
            'zip.regex'     => '郵便番号はハイフンありの8文字（例：123-4567）で入力してください',
        ];
    }
}
