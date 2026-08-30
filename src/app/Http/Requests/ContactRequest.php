<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'gender'     => ['required', 'in:1,2,3'],
            'email'      => ['required', 'email'],
            'tel1'       => ['required', 'digits_between:1,5'],
            'tel2'       => ['required', 'digits_between:1,5'],
            'tel3'       => ['required', 'digits_between:1,5'],
            'address'    => ['required', 'string'],
            'building'   => ['nullable', 'string'],
            'category'   => ['required', 'string', 'in:商品のお届けについて,商品の交換について,商品トラブル,ショップへのお問い合わせ,その他'],
            'content'    => ['required', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => '姓を入力してください',
            'last_name.required' => '名を入力してください',
            'gender.required' => '性別を選択してください',
            'gender.in' => '性別を選択してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'tel1.required' => '電話番号を入力してください',
            'tel1.digits_between' => '電話番号は5桁までの数字で入力してください',
            'tel2.required' => '電話番号を入力してください',
            'tel2.digits_between' => '電話番号は5桁までの数字で入力してください',
            'tel3.required' => '電話番号を入力してください',
            'tel3.digits_between' => '電話番号は5桁までの数字で入力してください',
            'address.required' => '住所を入力してください',
            'category.required' => 'お問い合わせの種類を選択してください',
            'category.in' => 'お問い合わせの種類を選択してください',
            'content.required' => 'お問い合わせ内容を入力してください',
            'content.max' => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $parts = [
                $this->input('tel1'),
                $this->input('tel2'),
                $this->input('tel3'),
            ];

            foreach ($parts as $part) {
                if (!is_string($part) || !ctype_digit($part)) {
                    return;
                }
            }

            if (strlen(implode('', $parts)) > 11) {
                $validator->errors()->add('tel1', '電話番号は11桁以内で入力してください');
            }
        });
    }
}
