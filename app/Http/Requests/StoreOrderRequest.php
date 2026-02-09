<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Book;
use Log;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        Log::info('Authorizing order request for user ID: ' . auth()->id());
        return auth()->check(); // false positive from intelephense.
    }

    public function rules(): array
{
    Log::info('(rules function)Validating order request for user ID: ' . auth()->id(), ['request' => $this->all()]);
    return [
        'order_items' => 'required|array|min:1',
        'order_items.*.book_id' => [
            'required',
            'exists:books,id',
            function ($attribute, $value, $fail) {
                $index = explode('.', $attribute)[1];
                $quantity = request()->input("order_items.{$index}.quantity", 1);
                $book = Book::find($value);
                Log::info("Validating book ID: {$value} with quantity: {$quantity}");

                if (!$book || $book->stock_quantity < $quantity) {
                    $fail("Insufficient stock for selected book.");
                }
            }
        ],
        'order_items.*.quantity' => 'required|integer|min:1|max:99',
    ];
}

    public function messages(): array
    {
        return [
            'order_items.required' => 'Your order is empty.',
            'order_items.*.book_id.exists' => 'One or more books are invalid.',
            'order_items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
