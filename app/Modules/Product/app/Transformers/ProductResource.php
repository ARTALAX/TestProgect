<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array{
     *     id: int,
     *     name: string,
     *     description?: null|string,
     *     price: float,
     *     weight?: null|float,
     *     category?: null|string,
     *     created_at: null|string,
     *     updated_at: null|string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'price' => $this->resource->price,
            'weight' => $this->resource->weight,
            'category' => $this->resource->category,
            'created_at' => $this->resource->created_at?->toDateTimeString(),
            'updated_at' => $this->resource->updated_at?->toDateTimeString(),
        ];
    }
}
