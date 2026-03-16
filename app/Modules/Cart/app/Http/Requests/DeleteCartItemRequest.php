<?php

namespace Modules\Cart\Http\Requests;

use App\Http\Requests\BaseRequest;

class DeleteCartItemRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cart_item_id' => 'required|exists:cart_items,id',
        ];
    }
}
