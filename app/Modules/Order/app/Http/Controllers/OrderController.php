<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Http\Requests\UpdateOrderStatusRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service) {}

    public function index(Request $request): JsonResponse
    {
        $orders = Order::with('items.product', 'address')
            ->where(column: 'user_id', operator: $request->user()->id)
            ->get()
        ;

        return response()->json($orders);
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize(ability: 'view', arguments: $order);

        return response()->json($order->load('items.product', 'address'));
    }

    #[OA\Post(
        path: '/api/orders',
        operationId: 'storeOrder',
        summary: 'Create order from current cart',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: 'Delivery address for the order',
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreOrderRequest',
                example: [
                    'region' => 'Moscow',
                    'city' => 'Moscow',
                    'street' => 'Tverskaya',
                    'house' => '10',
                    'entrance' => '2',
                    'apartment' => '45',
                    'postcode' => '125009',
                ]
            )
        ),
        tags: ['Orders'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Order created',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/OrderResponse',
                    example: [
                        'id' => 9,
                        'user_id' => 4,
                        'address_id' => 7,
                        'status' => 'created',
                        'total_price' => 998,
                        'created_at' => '2026-04-03T12:15:00.000000Z',
                        'updated_at' => '2026-04-03T12:15:00.000000Z',
                        'items' => [
                            [
                                'id' => 11,
                                'order_id' => 9,
                                'product_id' => 1,
                                'quantity' => 2,
                                'price' => 499,
                                'created_at' => '2026-04-03T12:15:00.000000Z',
                                'updated_at' => '2026-04-03T12:15:00.000000Z',
                                'product' => [
                                    'id' => 1,
                                    'name' => 'Margherita',
                                    'description' => 'Classic pizza with mozzarella and tomato sauce.',
                                    'price' => 499,
                                    'weight' => 450,
                                    'category' => 'pizza',
                                    'created_at' => '2026-04-03 12:00:00',
                                    'updated_at' => '2026-04-03 12:00:00',
                                ],
                            ],
                        ],
                        'address' => [
                            'id' => 7,
                            'user_id' => 4,
                            'region' => 'Moscow',
                            'city' => 'Moscow',
                            'street' => 'Tverskaya',
                            'house' => '10',
                            'entrance' => '2',
                            'apartment' => '45',
                            'postcode' => '125009',
                            'created_at' => '2026-04-03T12:15:00.000000Z',
                            'updated_at' => '2026-04-03T12:15:00.000000Z',
                        ],
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Authentication required',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'User role is not allowed to create orders',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation or business rule error',
                content: new OA\JsonContent(
                    examples: [
                        'validation' => new OA\Examples(
                            example: 'validation',
                            summary: 'Missing required fields',
                            value: [
                                'message' => 'The given data was invalid.',
                                'errors' => [
                                    'postcode' => [
                                        'The postcode field is required.',
                                    ],
                                ],
                            ]
                        ),
                        'emptyCart' => new OA\Examples(
                            example: 'emptyCart',
                            summary: 'Cart is empty',
                            value: [
                                'error' => 'В корзине нет товаров',
                            ]
                        ),
                        'limitsExceeded' => new OA\Examples(
                            example: 'limitsExceeded',
                            summary: 'Cart limits exceeded',
                            value: [
                                'error' => 'Превышен лимит товаров',
                            ]
                        ),
                    ],
                    oneOf: [
                        new OA\Schema(ref: '#/components/schemas/ValidationErrorResponse'),
                        new OA\Schema(ref: '#/components/schemas/BusinessErrorResponse'),
                    ]
                )
            ),
        ]
    )]
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->service->createOrder(user: $request->user(), addressData: $request->validated());

            return response()->json($order, Response::HTTP_CREATED);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): JsonResponse
    {
        $order = $this->service->updateStatus(order: $order, status: $request->status);

        return response()->json($order);
    }

    public function cancel(Request $request, Order $order): JsonResponse
    {
        $this->authorize(ability: 'cancel', arguments: $order);

        $this->service->cancelOrder(order: $order, user: $request->user());

        return response()->json([
            'message' => __(key: 'order::orders.canceled'),
        ]);
    }
}
