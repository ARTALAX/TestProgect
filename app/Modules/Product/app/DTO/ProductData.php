<?php

namespace Modules\Product\DTO;

class ProductData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly ?float $weight,
        public readonly string $category,
    ) {}

    /**
     * @param array{
     *     name: string,
     *     description?: string,
     *     price: float,
     *     weight?: float,
     *     category: string
     *
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            price: (float) $data['price'],
            weight: isset($data['weight']) ? (float) $data['weight'] : null,
            category: $data['category'],
        );
    }

    /**
     * @return array{
     *      name: string,
     *      description?: string,
     *      price: float,
     *      weight?: float,
     *      category: string
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'weight' => $this->weight,
            'category' => $this->category,
        ];
    }
}
