<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
            ],
            'items' => 'required|array|min:1',
            'items.*.product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')
            ],
            'items.*.quantity' => 'required|integer|min:1',
            'comment' => 'nullable|string|max:500'
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
            'user_id.required' => 'ID пользователя обязательно',
            'user_id.exists' => 'Пользователь не найден',
            'items.required' => 'Список товаров не может быть пустым',
            'items.*.product_id.required' => 'ID товара обязательно',
            'items.*.product_id.exists' => 'Товар не найден',
            'items.*.quantity.required' => 'Количество обязательно',
            'items.*.quantity.min' => 'Минимальное количество: 1',
            'comment.max' => 'Комментарий не должен превышать 500 символов'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Если user_id не указан, берем текущего аутентифицированного пользователя
        if (!$this->user_id && $user = auth()->user()) {
            $this->merge([
                'user_id' => $user->id
            ]);
        }
    }
}