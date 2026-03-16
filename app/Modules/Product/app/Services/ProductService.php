<?php

namespace Modules\Product\Services;

use Illuminate\Pagination\LengthAwarePaginator;
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
        $product->update(attributes: $dto->toArray());

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }
}
