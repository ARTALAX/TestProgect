<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Cart\Http\Requests\AddCartItemRequest;
use Modules\Cart\Http\Requests\DeleteCartItemRequest;
use Modules\Cart\Http\Requests\UpdateCartItemRequest;
use Modules\Cart\Services\CartService;

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
        $item = $cartService->updateItem(
            user: $request->user(),
            itemId: $request->cart_item_id,
            quantity: $request->quantity
        );

        return response()->json($item);
    }

    public function deleteItem(DeleteCartItemRequest $request, CartService $cartService): JsonResponse
    {
        $cartService->deleteItem(
            user: $request->user(),
            itemId: $request->cart_item_id
        );

        return response()->json(['message' => 'Товар удалён']);
    }
}
