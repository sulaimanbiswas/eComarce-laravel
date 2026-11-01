<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class HomeProductCatalog extends Component
{
    #[Locked]
    public int $sectionId;

    #[Locked]
    public int $categoryId = 0;

    #[Locked]
    public int $visibleCount = 10;

    public function mount(int $sectionId): void
    {
        $this->sectionId = $sectionId;
        $this->visibleCount = $this->initialCount();
    }

    private function section(): HomeSection
    {
        $section = HomeSection::query()->findOrFail($this->sectionId);
        abort_unless(($section->data->layout ?? '') === 'all-products' && ($section->data->section_enabled ?? true), 404);

        return $section;
    }

    private function initialCount(): int
    {
        return min(48, max(1, (int) ($this->section()->data->showcase_limit ?? 10)));
    }

    public function selectCategory(int $categoryId): void
    {
        $section = $this->section();
        abort_unless($section->data->showcase_filters ?? true, 403);
        abort_if($categoryId < 0, 422);
        if ($categoryId !== 0) {
            abort_unless(Category::query()->whereKey($categoryId)->where('is_enabled', true)->exists(), 422);
        }
        $this->categoryId = $categoryId;
        $this->visibleCount = $this->initialCount();
    }

    public function loadMore(): void
    {
        $batch = min(48, max(1, (int) ($this->section()->data->showcase_batch ?? 10)));
        $this->visibleCount = min(240, $this->visibleCount + $batch);
    }

    public function render(): View
    {
        $section = $this->section();
        $query = Product::query()->where('is_active', true)->whereNull('parent_id')->withCount('variations');
        $sectionCategories = $section->categories->pluck('id')->all();
        $ids = array_values(array_filter(array_map('intval', (array) $section->items)));
        if (($section->data->source ?? 'available') === 'specific') {
            if ($sectionCategories) {
                $query->whereHas('categories', fn ($categories) => $categories->whereIn('categories.id', $sectionCategories));
            } else {
                $query->whereIn('id', $ids);
            }
        }

        if ($this->categoryId !== 0) {
            $query->whereHas('categories', fn ($categories) => $categories->whereKey($this->categoryId)->where('is_enabled', true));
        }

        $sort = $section->data->showcase_sort ?? 'newest';
        match ($sort) {
            'price-low' => $query->orderBy('selling_price'),
            'price-high' => $query->orderByDesc('selling_price'),
            'name' => $query->orderBy('name'),
            'oldest' => $query->oldest('created_at'),
            default => $query->latest('created_at'),
        };
        $products = $query->orderBy('id')->limit($this->visibleCount + 1)->get();
        $hasMore = $products->count() > $this->visibleCount;
        $products = $products->take($this->visibleCount);
        $categories = Category::query()->where('is_enabled', true)
            ->whereHas('products', fn ($products) => $products->where('is_active', true)->whereNull('parent_id')
                ->when(($section->data->source ?? 'available') === 'specific' && ! $sectionCategories, fn ($products) => $products->whereIn('products.id', $ids)))
            ->when(($section->data->source ?? 'available') === 'specific' && $sectionCategories, fn ($categories) => $categories->whereIn('id', $sectionCategories))
            ->orderBy('order')->orderBy('name')->get(['id', 'name']);

        return view('livewire.home-product-catalog', array_merge(Setting::array(), compact('section', 'products', 'categories', 'hasMore')));
    }
}
