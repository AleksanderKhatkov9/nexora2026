<?php

namespace App\Services;

use App\Mail\NewOrderMail;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function store(array $data): Order
    {
        $order = $this->orderRepository->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'message' => $data['message'] ?? null,
            'channel' => $data['channel'] ?? null,
            'status' => Order::STATUS_NEW,
        ]);

        try {
            Mail::to(config('nexora.order_notification_email'))
                ->send(new NewOrderMail($order));
        } catch (Throwable $exception) {
            Log::error('Order notification mail failed', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);
        }

        return $order;
    }
}
