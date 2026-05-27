<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $attributes): Order
    {
        return Order::query()->create($attributes);
    }
}
