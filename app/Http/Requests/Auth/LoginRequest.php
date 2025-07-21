<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'phone' => [
                'required',
                'string',
                Rule::exists('users', 'phone')
            ],
            'password' => [
                'required',
                'string',
                'min:6'
            ]
        ];
    }

   /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Номер телефона обязателен',
            'phone.exists' => 'Пользователь с таким номером не найден',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Пароль должен быть не менее :min символов'
        ];
    }
}