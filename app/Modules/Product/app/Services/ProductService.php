<?php

namespace Modules\Product\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Product\DTO\ProductData;
use Modules\Product\Models\Product;

class ProductService
{
    public function create(ProductData $dto): Product
    {
        $product = Product::create($dto->toArray());
        Cache::forget('products_list');

        return $product;
    }

    public function update(Product $product, ProductData $dto): Product
    {
        $product->update(attributes: $dto->toArray());

        Cache::forget('products_list');

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();

        Cache::forget('products_list');
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Cache::remember('products_list', 3600, function () use ($perPage) {
            return Product::paginate($perPage);
        });
    }
}
