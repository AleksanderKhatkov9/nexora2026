<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'name' => 'Алексей К.',
                'phone' => '+375291112233',
                'email' => 'alexey@example.com',
                'message' => 'Нужен корпоративный сайт',
                'channel' => 'email',
                'status' => Order::STATUS_NEW,
                'created_at' => now()->subDays(1),
            ],
            [
                'name' => 'Мария С.',
                'phone' => '+375292223344',
                'email' => 'maria@example.com',
                'message' => 'Интернет-магазин под ключ',
                'channel' => 'telegram',
                'status' => Order::STATUS_IN_PROGRESS,
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Дмитрий П.',
                'phone' => '+375293334455',
                'email' => 'dmitry@example.com',
                'message' => 'Редизайн портала',
                'channel' => 'phone',
                'status' => Order::STATUS_DONE,
                'created_at' => now()->subDays(12),
            ],
            [
                'name' => 'Елена В.',
                'phone' => '+375294445566',
                'email' => 'elena@example.com',
                'message' => 'Landing page для рекламы',
                'channel' => 'email',
                'status' => Order::STATUS_DONE,
                'created_at' => now()->subDays(20),
            ],
            [
                'name' => 'Игорь М.',
                'phone' => '+375295556677',
                'email' => 'igor@example.com',
                'message' => null,
                'channel' => 'viber',
                'status' => Order::STATUS_CANCELLED,
                'created_at' => now()->subDays(35),
            ],
        ];

        foreach ($samples as $sample) {
            Order::query()->updateOrCreate(
                ['email' => $sample['email']],
                $sample
            );
        }
    }
}
