<x-mail::message>
# Новая заявка с сайта

**Имя:** {{ $order->name }}

**Телефон:** {{ $order->phone }}

**E-mail:** {{ $order->email }}

@if($order->channel)
**Способ связи:** {{ \App\Models\Order::channels()[$order->channel] ?? $order->channel }}
@endif

@if($order->message)
**Описание проекта:**

{{ $order->message }}
@endif

<x-mail::button :url="url('/nova/resources/orders/'.$order->id)">
Открыть в админке
</x-mail::button>

С уважением,<br>
{{ config('app.name') }}
</x-mail::message>
