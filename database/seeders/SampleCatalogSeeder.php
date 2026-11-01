<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SampleCatalogSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InitialSetupSeeder::ensureLocalEnvironment();

        $category = Category::query()->where('slug', 'power-tools')->orWhere('name', 'Power Tools')->first()
            ?? Category::query()->where('slug', 'local-demo-products')->where('name', 'Local Demo Products')->first();
        if ($category?->slug === 'local-demo-products') {
            $category->update(['name' => 'Power Tools', 'slug' => 'power-tools']);
        }
        $category ??= Category::query()->firstOrCreate(['slug' => 'power-tools'], [
            'name' => 'Power Tools', 'is_enabled' => true, 'order' => 1,
        ]);
        $brand = Brand::query()->where('slug', 'craftline-tools')->orWhere('name', 'Craftline Tools')->first()
            ?? Brand::query()->where('slug', 'local-demo-brand')->where('name', 'Local Demo Brand')->first();
        if ($brand?->slug === 'local-demo-brand') {
            $brand->update(['name' => 'Craftline Tools', 'slug' => 'craftline-tools']);
        }
        $brand ??= Brand::query()->firstOrCreate(['slug' => 'craftline-tools'], [
            'name' => 'Craftline Tools', 'is_enabled' => true,
        ]);
        $category->seo()->firstOrCreate([]);
        $brand->seo()->firstOrCreate([]);

        $productIds = [];
        foreach ([
            ['name' => 'Electric Wood Planer', 'slug' => 'electric-wood-planer', 'sku' => 'CL-PLANER-001', 'legacy_sku' => 'LOCAL-DEMO-001', 'legacy_name' => 'Local Demo Product', 'legacy_slug' => 'local-demo-product', 'price' => 1200, 'selling_price' => 1000, 'image' => 'product-1.jpg', 'summary' => 'Electric wood planer for smoothing timber and preparing wood surfaces.'],
            ['name' => 'Circular Saw', 'slug' => 'circular-saw', 'sku' => 'CL-SAW-001', 'legacy_sku' => 'LOCAL-DEMO-002', 'legacy_name' => 'Local Demo Accessory', 'legacy_slug' => 'local-demo-accessory', 'price' => 600, 'selling_price' => 500, 'image' => 'product-2.jpg', 'summary' => 'Circular saw for straight cuts in timber and sheet materials.'],
        ] as $sample) {
            $product = Product::query()->where('sku', $sample['sku'])->orWhere('sku', $sample['legacy_sku'])->first();
            if ($product && $product->sku === $sample['legacy_sku'] && $product->name === $sample['legacy_name'] && $product->slug === $sample['legacy_slug']) {
                $product->fill(['name' => $sample['name'], 'slug' => $sample['slug'], 'sku' => $sample['sku']]);
                if ($product->description === '<p>A sample product for local setup. Replace it with your own catalog.</p>') {
                    $product->description = '<p>'.$sample['summary'].'</p>';
                }
                if ($product->short_description === 'Sample product for local development.') {
                    $product->short_description = $sample['summary'];
                }
                if ($product->stock_group === 'local-demo') {
                    $product->stock_group = 'power-tools';
                }
                $product->save();
            }
            $product ??= Product::query()->firstOrCreate(['slug' => $sample['slug']], [
                'name' => $sample['name'], 'sku' => $sample['sku'], 'brand_id' => $brand->id,
                'description' => '<p>'.$sample['summary'].'</p>',
                'short_description' => $sample['summary'],
                'desc_img' => false, 'desc_img_pos' => 'after_content',
                'price' => $sample['price'], 'selling_price' => $sample['selling_price'],
                'suggested_price' => (string) $sample['price'],
                'average_purchase_price' => (int) ($sample['selling_price'] * 0.6),
                'should_track' => true, 'stock_count' => 25, 'stock_group' => 'power-tools',
                'is_active' => true, 'new_arrival' => true, 'shipping_inside' => 60, 'shipping_outside' => 120,
                'packaging_charge' => 0,
            ]);
            $productIds[] = $product->id;

            $image = $this->seedImage($sample['image'], $sample['name']);
            if ($product->wasRecentlyCreated) {
                $product->seo()->firstOrCreate([]);
                $product->categories()->syncWithoutDetaching([$category->id]);
                $product->images()->syncWithoutDetaching([$image->id => ['img_type' => 'base', 'order' => 0]]);
            }
        }

        $section = HomeSection::query()->where('title', 'Workshop Essentials')->first()
            ?? HomeSection::query()->where('title', 'Local Demo Products')->where('type', 'pure-grid')->first();
        if ($section?->title === 'Local Demo Products') {
            $section->update(['title' => 'Workshop Essentials']);
        }
        $section ??= HomeSection::query()->firstOrCreate(['title' => 'Workshop Essentials'], [
            'type' => 'pure-grid', 'items' => $productIds, 'order' => 1,
            'data' => ['rows' => 1, 'cols' => 4, 'source' => 'specific'],
        ]);
        if ($section->wasRecentlyCreated) {
            $section->seo()->firstOrCreate([]);
            $section->categories()->syncWithoutDetaching([$category->id]);
        }
    }

    private function seedImage(string $filename, string $productName): Image
    {
        $source = public_path('strokya/images/products/'.$filename);
        $relativePath = 'local-demo/'.$filename;
        $disk = Storage::disk('public');
        if (! $disk->exists($relativePath)) {
            $disk->put($relativePath, File::get($source));
        }

        $image = Image::query()->firstOrCreate(['path' => '/storage/'.$relativePath], [
            'disk' => 'public', 'filename' => $productName.'.jpg', 'extension' => 'jpg',
            'mime' => 'image/jpeg', 'size' => File::size($source),
        ]);
        if ($image->filename === 'Local demo '.$filename) {
            $image->update(['filename' => $productName.'.jpg']);
        }

        return $image;
    }
}
