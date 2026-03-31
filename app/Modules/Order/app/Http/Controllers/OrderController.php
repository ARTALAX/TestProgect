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
