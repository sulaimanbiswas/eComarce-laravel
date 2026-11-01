<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiCheckoutRequest;
use App\Http\Resources\ProductResource;
use App\Livewire\Checkout;
use App\Services\OrderProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class LivewireCheckoutController extends Controller
{
    public function __invoke(ApiCheckoutRequest $request, OrderProductService $orderProducts): JsonResponse
    {
        $data = $request->validated();
        $items = $orderProducts->resolveItems($data['cart']);

        $livewire = new Checkout;
        $livewire->mount();

        // Clear existing cart
        cart()->destroy();

        // Add items to cart
        foreach ($items as $item) {
            $cartItem = (new ProductResource($item['product']))->toCartItem($item['quantity']);
            cart()->add([
                'id' => $cartItem['id'],
                'name' => $cartItem['name'],
                'qty' => $cartItem['quantity'],
                'price' => $cartItem['price'],
                'options' => [
                    'purchase_price' => $cartItem['purchase_price'],
                    'parent_id' => $cartItem['parent_id'],
                    'slug' => $cartItem['slug'],
                    'image' => $cartItem['image'],
                    'category' => $cartItem['category'],
                    'shipping_inside' => $cartItem['shipping_inside'],
                    'shipping_outside' => $cartItem['shipping_outside'],
                    'max' => $orderProducts->maximumQuantity($item['product']),
                    'retail_price' => $cartItem['retail_price'],
                ],
            ]);
        }

        $livewire->name = $request->input('name');
        $livewire->phone = $request->input('phone');
        $livewire->email = $request->input('email');
        $livewire->address = $request->input('address');
        $livewire->note = $request->input('note');
        $livewire->shipping = $request->input('shipping');

        $livewire->cartUpdated();
        if ($livewire->checkout() instanceof RedirectResponse && session('error')) {
            return response()->json(['message' => session('error')], 422);
        }

        if (! $livewire->order) {
            throw ValidationException::withMessages(['cart' => 'The order could not be placed. Please review your cart.']);
        }

        return response()->json(['message' => 'Order placed successfully.', 'order' => $livewire->order, 'tracking_url' => URL::temporarySignedRoute('thank-you', now()->addDays(7), ['order' => $livewire->order->id])]);
    }
}
