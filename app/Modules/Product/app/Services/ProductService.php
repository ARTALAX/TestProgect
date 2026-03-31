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

        Cache::tags('products')->flush();

        return $product;
    }

    public function update(Product $product, ProductData $dto): Product
    {
        $product->update(attributes: $dto->toArray());

        Cache::tags('products')->flush();

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();

        Cache::tags('products')->flush();
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $page = request(key: 'page', default: 1);
        $cacheKey = "products_list_page_{$page}_per_{$perPage}";

        return Cache::tags(['products'])->remember(key: $cacheKey, ttl: 3600, callback: function () use ($perPage) {
            return Product::paginate($perPage);
        });
    }
}
