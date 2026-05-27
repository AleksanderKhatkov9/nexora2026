<?php

namespace Tests\Feature;

use App\Mail\NewOrderMail;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_submitted_via_api(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/orders', [
            'name' => 'Иван Петров',
            'phone' => '+375291234567',
            'email' => 'ivan@example.com',
            'message' => 'Нужен корпоративный сайт',
            'channel' => 'telegram',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.id', 1);

        $this->assertDatabaseHas('orders', [
            'name' => 'Иван Петров',
            'phone' => '+375291234567',
            'email' => 'ivan@example.com',
            'channel' => 'telegram',
            'status' => Order::STATUS_NEW,
        ]);

        Mail::assertSent(NewOrderMail::class);
    }

    public function test_order_requires_valid_fields(): void
    {
        $response = $this->postJson('/api/orders', []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name', 'phone', 'email']);
    }

    public function test_order_is_saved_even_when_mail_fails(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP unavailable'));

        $response = $this->postJson('/api/orders', [
            'name' => 'Иван Петров',
            'phone' => '+375291234567',
            'email' => 'ivan@example.com',
            'message' => 'Тест',
            'channel' => 'email',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('orders', ['email' => 'ivan@example.com']);
    }
}
