<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|required|in:pending,processing,shipped,completed,cancelled',
            'order_items' => 'sometimes|required|array|min:1',
            'order_items.*.book_id' => 'required_with:order_items|exists:books,id',
            'order_items.*.quantity' => 'required_with:order_items|integer|min:1',
        ];
    }
}
