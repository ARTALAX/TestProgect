<?php

namespace Modules\Product\Services;

use Modules\Product\DTO\ProductData;
use Modules\Product\Models\Product;


class ProductService
{
    public function create(ProductData $dto): Product
    {
        return Product::create($dto->toArray());
    }

    public function update(Product $product, ProductData $dto): Product
    {
        $product->update($dto->toArray());
        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function paginate(int $perPage = 15)
    {
        return Product::paginate($perPage);
    }

}
