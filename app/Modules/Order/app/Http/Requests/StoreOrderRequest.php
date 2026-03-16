<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\BaseRequest;

class StoreOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'region' => 'required|string',
            'city' => 'required|string',
            'street' => 'required|string',
            'house' => 'required|string',
            'entrance' => 'nullable|string',
            'apartment' => 'nullable|string',
            'postcode' => 'required|string',
        ];
    }
}
