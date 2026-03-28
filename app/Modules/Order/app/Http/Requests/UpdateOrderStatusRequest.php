<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Order\Enums\OrderStatus;

class UpdateOrderStatusRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(type: OrderStatus::class),
            ],
        ];
    }
}
