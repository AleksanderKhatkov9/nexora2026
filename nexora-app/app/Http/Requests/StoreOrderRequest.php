<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
            'channel' => ['nullable', 'string', Rule::in(array_keys(Order::channels()))],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'имя',
            'phone' => 'телефон',
            'email' => 'e-mail',
            'message' => 'сообщение',
            'channel' => 'способ связи',
        ];
    }
}
