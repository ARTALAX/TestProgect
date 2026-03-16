<?php

namespace Modules\Cart\Http\Requests;

use App\Http\Requests\BaseRequest;

class AddCartItemRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
