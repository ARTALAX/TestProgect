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

        return response()->json(['message' => 'Товар удалён'], Response::HTTP_NO_CONTENT);
    }
}
