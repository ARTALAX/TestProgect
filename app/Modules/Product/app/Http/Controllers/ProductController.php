<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Product\DTO\ProductData;
use Modules\Product\Http\Requests\CreateProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Models\Product;
use Modules\Product\Resources\ProductResource;
use Modules\Product\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $products = $this->service->paginate();

        return ProductResource::collection(resource: $products);
    }

    public function show(Product $product): ProductResource
    {
        return ProductResource::make($product);
    }

    public function store(CreateProductRequest $request): ProductResource
    {
        $dto = ProductData::fromArray(data: $request->validated());
        $product = $this->service->create(dto: $dto);

        return ProductResource::make($product);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $dto = ProductData::fromArray(data: $request->validated());
        $updated = $this->service->update(product: $product, dto: $dto);

        return ProductResource::make($updated);
    }

    public function destroy(Product $product): Response
    {
        $this->service->delete(product: $product);

        return response()->noContent();
    }
}
