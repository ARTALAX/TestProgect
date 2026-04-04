<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Http\Requests\AddCartItemRequest;
use Modules\Cart\Http\Requests\DeleteCartItemRequest;
use Modules\Cart\Http\Requests\UpdateCartItemRequest;
use Modules\Cart\Services\CartService;
use Modules\CartItem\Models\CartItem;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    public function show(Request $request, CartService $cartService): JsonResponse
    {
        $cart = $cartService->getUserCart(user: $request->user());

        return response()->json([
            'items' => $cart->items,
            'total' => $cart->total(),
        ]);
    }

    #[OA\Post(
        path: '/api/cart/add',
        operationId: 'addCartItem',
        summary: 'Add product to cart',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: 'Product and quantity to add to the authenticated user cart',
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/CartAddItemRequest',
                example: [
                    'product_id' => 1,
                    'quantity' => 2,
                ]
            )
        ),
        tags: ['Cart'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cart item created or quantity updated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CartItemResponse',
                    example: [
                        'id' => 5,
                        'cart_id' => 3,
                        'product_id' => 1,
                        'quantity' => 2,
                        'created_at' => '2026-04-03T12:10:00.000000Z',
                        'updated_at' => '2026-04-03T12:10:00.000000Z',
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
                description: 'User role is not allowed to access cart',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ValidationErrorResponse',
                    example: [
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'quantity' => [
                                'The quantity field must be at least 1.',
                            ],
                        ],
                    ]
                )
            ),
        ]
    )]
    public function addItem(AddCartItemRequest $request, CartService $cartService): JsonResponse
    {
        $item = $cartService->addItem(
            user: $request->user(),
            productId: $request->product_id,
            quantity: $request->quantity
        );

        return response()->json($item);
    }

    public function updateItem(UpdateCartItemRequest $request, CartService $cartService): JsonResponse
    {
        $item = CartItem::findOrFail($request->cart_item_id);

        $this->authorize(ability: 'update', arguments: $item);

        $item = $cartService->updateItem(
            item: $item,
            quantity: $request->quantity
        );

        return response()->json($item);
    }

    public function deleteItem(DeleteCartItemRequest $request, CartService $cartService): JsonResponse
    {
        $item = CartItem::findOrFail($request->cart_item_id);

        $this->authorize(ability: 'delete', arguments: $item);

        $cartService->deleteItem(item: $item);

        return response()->json(['message' => __(key: 'cart::carts.item_deleted')], Response::HTTP_NO_CONTENT);
    }
}
