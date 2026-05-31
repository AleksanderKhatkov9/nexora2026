<?php

namespace App\Http\Controllers\Backend\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->store($request->validated());

        return response()->json([
            'message' => 'Заявка успешно отправлена. Я свяжусь с вами в ближайшее время.',
            'data' => [
                'id' => $order->id,
            ],
        ], 201);
    }
}
