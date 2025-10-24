<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'pay_method' => ['required', 'in:card,convenience'],
        ];
    }
    public function messages(): array
    {
        return [
            'pay_method.required' => '支払い方法を選択してください。',
        ];
    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validatorInstance) {
            // 現在ログイン中のユーザー情報を取得
            $currentUser = $this->user();

            // もし郵便番号または住所1が空なら、配送先が未登録とみなす
            $isZipEmpty = empty($currentUser->zip);
            $isAddressEmpty = empty($currentUser->address1);

            if ($isZipEmpty || $isAddressEmpty) {
                $validatorInstance->errors()->add(
                    'ship',
                    '配送先が未設定です。「変更する」から住所を登録してください。'
                );
            }
        });
    }
}
