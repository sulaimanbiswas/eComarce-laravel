<?php

namespace App\Livewire\Fabricator;

use App\Livewire\ProductDetail;
use App\Models\Product;
use App\Services\OrderProductService;
use Illuminate\Validation\ValidationException;

class Checkout extends \App\Livewire\Checkout
{
    public ?string $layout = null;

    public Product $product;

    #[\Override]
    public function increaseQuantity($id): void
    {
        if (! ($row = cart($id)) && ($product = Product::find($id))) {
            ProductDetail::landing($product);
        } elseif ($row->qty < $row->options->max || $row->options->max === -1) {
            $product = Product::find($row->id);
            throw_unless($product, ValidationException::withMessages(['products' => 'This product is no longer available.']));
            app(OrderProductService::class)->validateQuantity($product, (int) $row->qty + 1);
            cart()->update($row->rowId, $row->qty + 1);
        }
        $this->cartUpdated();
    }

    #[\Override]
    public function decreaseQuantity($id): void
    {
        if (! ($row = cart($id)) && ($product = Product::find($id))) {
            ProductDetail::landing($product);
        } elseif ($row->qty > 1) {
            cart()->update($row->rowId, $row->qty - 1);
        }
        $this->cartUpdated();
    }

    #[\Override]
    public function mount(): void
    {
        ProductDetail::landing($this->product);
        parent::mount();
    }

    #[\Override]
    public function render()
    {
        $this->cartUpdated();

        return view('livewire.fabricator.checkout');
    }
}
