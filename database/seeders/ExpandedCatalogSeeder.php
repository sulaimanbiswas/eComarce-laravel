<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\ResponseCache\Facades\ResponseCache;

class ExpandedCatalogSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        InitialSetupSeeder::ensureLocalEnvironment();

        $createdCount = 0;
        (new Product)->getConnection()->transaction(function () use (&$createdCount): void {
            $brand = Brand::query()->firstOrCreate(['slug' => 'craftline-tools'], [
                'name' => 'Craftline Tools', 'is_enabled' => true,
            ]);
            $brand->seo()->firstOrCreate([]);
            $featuredIds = [];
            $sectionOrder = ((int) HomeSection::query()->max('order')) + 1;
            $categoryOrder = ((int) Category::query()->max('order')) + 1;

            foreach ($this->catalog() as $categoryIndex => $group) {
                $category = Category::query()->firstOrCreate(['slug' => $group['slug']], [
                    'name' => $group['name'], 'is_enabled' => true, 'order' => $categoryOrder + $categoryIndex,
                ]);
                $category->seo()->firstOrCreate([]);
                $categoryIsNew = $category->wasRecentlyCreated;
                $ids = [];

                foreach ($group['products'] as $productIndex => [$name, $photo, $sellingPrice]) {
                    $slug = Str::slug($name);
                    $sku = 'CATALOG-'.strtoupper($group['code']).'-'.str_pad((string) ($productIndex + 1), 3, '0', STR_PAD_LEFT);
                    $product = Product::query()->where('sku', $sku)->orWhere('slug', $slug)->first();

                    if (! $product) {
                        $price = (int) (ceil($sellingPrice * (1.10 + $productIndex * 0.02) / 10) * 10);
                        $summary = $name.' for everyday workshop and home improvement projects.';
                        $product = Product::query()->create([
                            'name' => $name, 'slug' => $slug, 'sku' => $sku, 'brand_id' => $brand->id,
                            'description' => '<p>'.$summary.'</p><p>Part of the '.$group['name'].' collection. This is sample catalog data for local development; prices, specifications and photographs are illustrative.</p>',
                            'short_description' => $summary,
                            'desc_img' => false, 'desc_img_pos' => 'after_content',
                            'price' => $price, 'selling_price' => $sellingPrice, 'suggested_price' => (string) $price,
                            'average_purchase_price' => (int) round($sellingPrice * 0.65),
                            'should_track' => true, 'stock_count' => 12 + ($categoryIndex * 7 + $productIndex * 3) % 45,
                            'stock_group' => 'sample-catalog-'.$group['slug'],
                            'is_active' => true, 'new_arrival' => $productIndex >= 4, 'hot_sale' => $productIndex < 2,
                            'shipping_inside' => 60, 'shipping_outside' => 120, 'packaging_charge' => 0,
                        ]);
                        $createdCount++;
                        $image = $this->image($photo, $name);
                        $product->images()->attach($image->id, ['img_type' => 'base', 'order' => 0]);
                        $product->categories()->attach($category->id);
                        $product->seo()->firstOrCreate([]);
                    }

                    $ids[] = $product->id;
                    if ($productIndex === 0) {
                        $featuredIds[] = $product->id;
                        if ($categoryIsNew) {
                            $category->update(['image_id' => $product->images()->first()?->id]);
                        }
                    }
                }

                $this->section($group['name'].' Collection', $ids, $sectionOrder + $categoryIndex, $group['name']);
            }

            $this->section('Workshop Highlights', $featuredIds, ((int) HomeSection::query()->min('order')) - 1, 'Best Selling Products');
        });

        foreach (['brands', 'brands:carousel', 'categories:carousel', 'categories:nested:enabled', 'categories:nested:all', 'homesections', 'api_categories:all'] as $key) {
            cacheMemo()->forget($key);
            Cache::forget($key);
        }
        foreach (range(0, 15) as $count) {
            cacheMemo()->forget('categories:nested:'.$count);
            Cache::forget('categories:nested:'.$count);
        }
        foreach (['api_categories', 'api_category', 'api_sections', 'product_filters', 'section_products', 'related_products'] as $namespace) {
            cacheInvalidateNamespace($namespace);
        }
        if (config('responsecache.enabled', false)) {
            ResponseCache::clear();
        }

        $this->command?->info($createdCount.' new sample products added across 6 categories. Existing catalog entries were preserved.');
    }

    private function image(int $number, string $name): Image
    {
        $filename = 'product-'.$number.'.jpg';
        $source = public_path('strokya/images/products/'.$filename);
        $path = 'sample-catalog/'.$filename;
        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, File::get($source));
        }

        return Image::query()->firstOrCreate(['path' => '/storage/'.$path], [
            'filename' => $name.'.jpg', 'disk' => 'public', 'extension' => 'jpg',
            'mime' => 'image/jpeg', 'size' => File::size($source),
        ]);
    }

    /** @param list<int> $ids */
    private function section(string $title, array $ids, int $order, string $heading): void
    {
        $section = HomeSection::query()->firstOrCreate(['title' => $title], [
            'type' => 'pure-grid', 'items' => $ids, 'order' => $order,
            'data' => [
                'source' => 'specific', 'rows' => 2, 'cols' => 3, 'layout' => 'showcase',
                'showcase_heading' => $heading, 'showcase_limit' => 6,
                'showcase_background' => $heading === 'Best Selling Products' ? '#eaf2e8' : '#fbf9f5',
                'showcase_accent' => '#c89536',
            ],
        ]);
        if ($section->wasRecentlyCreated) {
            $section->seo()->firstOrCreate([]);
        }
    }

    /** @return list<array{name: string, slug: string, code: string, products: list<array{string, int, int}>}> */
    private function catalog(): array
    {
        return [
            ['name' => 'Drills & Drivers', 'slug' => 'drills-drivers', 'code' => 'DRL', 'products' => [
                ['Cordless Drill Driver 12V', 3, 3200],
                ['Cordless Drill Driver 18V', 16, 4800],
                ['Compact Cordless Drill 16V', 3, 3900],
                ['Rotary Hammer Drill 800W', 4, 6500],
                ['Rotary Hammer Drill 1050W', 4, 8200],
                ['Bench Pillar Drill 350W', 6, 12500],
            ]],
            ['name' => 'Cutting Tools', 'slug' => 'cutting-tools', 'code' => 'CUT', 'products' => [
                ['Circular Saw 185mm', 2, 5800],
                ['Circular Saw 235mm', 2, 7800],
                ['Variable Speed Jigsaw 650W', 15, 3600],
                ['Pendulum Jigsaw 750W', 15, 4400],
                ['Petrol Chainsaw 18 Inch', 12, 11800],
                ['Petrol Chainsaw 20 Inch', 12, 14200],
            ]],
            ['name' => 'Woodworking Tools', 'slug' => 'woodworking-tools', 'code' => 'WOOD', 'products' => [
                ['Electric Wood Planer 82mm', 1, 5200],
                ['Electric Wood Planer 900W', 1, 6100],
                ['Plunge Router 1200W', 5, 7200],
                ['Plunge Router 1600W', 5, 9600],
                ['Woodworking Jigsaw 500W', 15, 3100],
                ['Precision Bench Drill 500W', 6, 15400],
            ]],
            ['name' => 'Hand Tools', 'slug' => 'hand-tools', 'code' => 'HAND', 'products' => [
                ['Combination Pliers 6 Inch', 7, 320],
                ['Combination Pliers 8 Inch', 7, 450],
                ['Socket Wrench Set 46 Piece', 11, 1800],
                ['Socket Wrench Set 94 Piece', 11, 3200],
                ['Heavy Duty Pipe Wrench 10 Inch', 9, 650],
                ['Heavy Duty Pipe Wrench 14 Inch', 9, 890],
            ]],
            ['name' => 'Plumbing Supplies', 'slug' => 'plumbing-supplies', 'code' => 'PLMB', 'products' => [
                ['Braided Water Connector 12 Inch', 8, 180],
                ['Braided Water Connector 18 Inch', 8, 220],
                ['Braided Water Connector 24 Inch', 8, 280],
                ['Brass Ball Valve Half Inch', 10, 380],
                ['Brass Ball Valve Three Quarter Inch', 10, 520],
                ['Brass Ball Valve One Inch', 10, 690],
            ]],
            ['name' => 'Air Tools & Grinders', 'slug' => 'air-tools-grinders', 'code' => 'AIR', 'products' => [
                ['Portable Air Compressor 24L', 14, 12800],
                ['Workshop Air Compressor 50L', 14, 18500],
                ['Twin Cylinder Air Compressor 100L', 14, 28500],
                ['Angle Grinder 100mm 750W', 13, 2800],
                ['Angle Grinder 115mm 900W', 13, 3500],
                ['Angle Grinder 125mm 1100W', 13, 4200],
            ]],
        ];
    }
}
