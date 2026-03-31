<?php

namespace Modules\Product\Http\Requests;

use App\Http\Requests\BaseRequest;

class CreateProductRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|string[]>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'category' => 'string|max:255',
        ];
    }

    // Determine if the user is authorized to make this request.
}
