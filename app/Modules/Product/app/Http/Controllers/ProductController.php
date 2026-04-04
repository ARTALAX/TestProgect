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
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    #[OA\Get(
        path: '/api/products',
        operationId: 'getProducts',
        summary: 'Get product list',
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of products',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductCollectionResponse',
                    example: [
                        'data' => [
                            [
                                'id' => 1,
                                'name' => 'Margherita',
                                'description' => 'Classic pizza with mozzarella and tomato sauce.',
                                'price' => 499,
                                'weight' => 450,
                                'category' => 'pizza',
                                'created_at' => '2026-04-03 12:00:00',
                                'updated_at' => '2026-04-03 12:00:00',
                            ],
                            [
                                'id' => 2,
                                'name' => 'Cola',
                                'description' => 'Refreshing drink 0.5L.',
                                'price' => 149,
                                'weight' => 500,
                                'category' => 'drink',
                                'created_at' => '2026-04-03 12:00:00',
                                'updated_at' => '2026-04-03 12:00:00',
                            ],
                        ],
                        'links' => [
                            'first' => 'http://localhost/api/products?page=1',
                            'last' => 'http://localhost/api/products?page=1',
                            'prev' => null,
                            'next' => null,
                        ],
                        'meta' => [
                            'current_page' => 1,
                            'from' => 1,
                            'last_page' => 1,
                            'path' => 'http://localhost/api/products',
                            'per_page' => 15,
                            'to' => 2,
                            'total' => 2,
                            'links' => [
                                [
                                    'url' => null,
                                    'label' => '&laquo; Previous',
                                    'active' => false,
                                ],
                                [
                                    'url' => 'http://localhost/api/products?page=1',
                                    'label' => '1',
                                    'active' => true,
                                ],
                                [
                                    'url' => null,
                                    'label' => 'Next &raquo;',
                                    'active' => false,
                                ],
                            ],
                        ],
                    ]
                )
            ),
        ]
    )]
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
