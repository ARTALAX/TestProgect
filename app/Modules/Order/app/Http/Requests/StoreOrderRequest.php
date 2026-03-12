<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // или через политику OrderPolicy
    }

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
