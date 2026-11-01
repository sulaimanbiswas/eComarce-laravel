@component('mail::message')
# Hello {{ $order->name }},

Your order has been successfully placed.

@component('mail::button', ['url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('track-order', now()->addDays(7), ['order' => $order->id])])
View Order #{{ $order->id }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
