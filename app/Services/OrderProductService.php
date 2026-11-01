<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Validation\ValidationException;

class OrderProductService
{
    public function maximumQuantity(Product $product): int
    {
        $limit = max(0, (int) (setting('fraud')->max_qty_per_product ?? 3));

        return $product->should_track ? min($limit, max(0, (int) $product->stock_count)) : $limit;
    }

    public function validateQuantity(Product $product, int $quantity, string $field = 'products'): void
    {
        if (! $product->is_active || ($product->parent_id && ! $product->parent?->is_active)) {
            throw ValidationException::withMessages([$field => 'This product is no longer available.']);
        }

        if ($quantity < 1 || $quantity > $this->maximumQuantity($product)) {
            throw ValidationException::withMessages([$field => 'The requested quantity exceeds the available stock or order limit.']);
        }
    }

    /**
     * @param  array<int, array{id: int|string, quantity: int|string, variation_id?: int|string|null}>  $items
     * @return array<int, array{product: Product, quantity: int}>
     */
    public function resolveItems(array $items): array
    {
        $ids = collect($items)->pluck('id')->merge(collect($items)->pluck('variation_id')->filter())->unique()->all();
        $products = Product::query()->with(['parent.categories', 'parent.images', 'categories', 'images'])->withCount('variations')->whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $resolved = [];

        foreach ($items as $index => $item) {
            $product = $products->get($item['id']);
            $variationId = $item['variation_id'] ?? null;
            if (! $product) {
                throw ValidationException::withMessages(["items.{$index}.id" => 'This product no longer exists.']);
            }

            if ($variationId) {
                $variation = $products->get($variationId);
                if (! $variation || (int) $variation->parent_id !== (int) $product->id || $product->parent_id) {
                    throw ValidationException::withMessages(["items.{$index}.variation_id" => 'This variation does not belong to the selected product.']);
                }
                $product = $variation;
            } elseif (! $product->parent_id && $product->variations_count > 1) {
                throw ValidationException::withMessages(["items.{$index}.variation_id" => 'Please select a product variation.']);
            }

            $quantity = ($resolved[$product->id]['quantity'] ?? 0) + (int) $item['quantity'];
            $this->validateQuantity($product, $quantity, "items.{$index}.quantity");
            $resolved[$product->id] = ['product' => $product, 'quantity' => $quantity];
        }

        return $resolved;
    }
}
