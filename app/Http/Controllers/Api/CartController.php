<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\OrderProductService;
use Azmolla\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function add(AddCartItemRequest $request, OrderProductService $orderProducts): JsonResponse
    {
        $product = Product::with('parent')->findOrFail($request->product_id);
        if (! ($product->parent ?? $product)->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is inactive and cannot be added to cart.',
            ], 400);
        }

        $quantity = $request->quantity ?? 1;
        $instance = $request->instance ?? 'default';
        $existingQuantity = in_array($instance, ['kart', 'landing'], true) ? 0 : (int) cart()->instance($instance)->content()->where('id', $product->id)->sum('qty');
        $orderProducts->validateQuantity($product, $existingQuantity + (int) $quantity, 'quantity');

        try {
            // Follow the HasCart trait logic but avoid problematic services
            session(['kart' => $instance]);
            if (in_array($instance, ['kart', 'landing'], true)) {
                Cart::instance($instance)->destroy();
            }

            $maxQuantity = $orderProducts->maximumQuantity($product);

            // Use ProductResource to get proper cart item data
            $productData = (new ProductResource($product))->toCartItem($quantity);
            $productData['max'] = $maxQuantity;
            $productData['retail_price'] = $product->getPrice($quantity);

            cart()->instance($instance)->add(
                $product->id,
                $product->varName,
                $quantity,
                $productData['price'], // this is the wholesale price
                $productData
            );

            // Store cart in session
            storeOrUpdateCart();

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_count' => cart()->instance($instance)->count(),
                'cart_total' => cart()->instance($instance)->total(),
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                ],
                'instance' => $instance,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart: '.$e->getMessage(),
            ], 500);
        }
    }

    public function get(Request $request)
    {
        $instance = $request->get('instance', 'default');

        return response()->json([
            'success' => true,
            'cart' => cart()->instance($instance)->content(),
            'cart_count' => cart()->instance($instance)->count(),
            'cart_total' => cart()->instance($instance)->total(),
        ]);
    }
}
