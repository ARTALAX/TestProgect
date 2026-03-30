<?php

namespace Modules\Product\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Product\Models\Product;

class ProductObserver
{
    public function created(Product $product): void
    {
        Cache::tags('products')->flush();
    }

    public function updated(Product $product): void
    {
        Cache::tags('products')->flush();
    }

    public function deleted(Product $product): void
    {
        Cache::tags('products')->flush();
    }
}
