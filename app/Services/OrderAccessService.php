<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderAccessService
{
    public function authorize(Request $request, Order $order): void
    {
        $admin = $request->user('admin');
        if ($admin?->is_active && ($admin->is(['admin', 'manager']) || ($admin->is('salesman') && (int) $order->admin_id === (int) $admin->id))) {
            return;
        }

        $user = $request->user('user');
        if ($user?->is_active && (int) $order->user_id === (int) $user->id) {
            return;
        }

        if (($request->hasSession() && $request->session()->get('orders.authorized.'.$order->id) === true) || $request->hasValidSignature()) {
            return;
        }

        $phone = $request->input('phone');
        abort_unless(is_string($phone) && $this->phoneMatches($order, $phone), 404);

        if ($request->hasSession()) {
            $request->session()->put('orders.authorized.'.$order->id, true);
        }
    }

    public function phoneMatches(Order $order, string $phone): bool
    {
        $normalizedPhone = $this->normalizePhone($phone);

        return preg_match('/^8801\d{9}$/', $normalizedPhone) === 1
            && hash_equals($this->normalizePhone((string) $order->phone), $normalizedPhone);
    }

    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        return strlen($phone) === 11 && str_starts_with($phone, '01') ? '88'.$phone : $phone;
    }
}
