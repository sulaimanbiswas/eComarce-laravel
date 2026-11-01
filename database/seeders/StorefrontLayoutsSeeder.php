<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\ResponseCache\Facades\ResponseCache;

class StorefrontLayoutsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InitialSetupSeeder::ensureLocalEnvironment();
        $layouts = ['Drills & Drivers' => ['grid', 5], 'Cutting Tools' => ['horizontal', 3], 'Woodworking Tools' => ['slider', 4], 'Hand Tools' => ['compact', 4], 'Plumbing Supplies' => ['grid', 5], 'Air Tools & Grinders' => ['horizontal', 3]];
        foreach ($layouts as $category => [$layout, $columns]) {
            $section = HomeSection::query()->where('title', $category.' Collection')->first();
            if (! $section || ($section->data->layout ?? '') !== 'showcase') {
                continue;
            }
            $data = (array) $section->data;
            $data['layout'] = $layout;
            $data['showcase_columns'] = $columns;
            $data['showcase_view_all'] = true;
            $section->update(['data' => $data]);
        }

        $section = HomeSection::query()->firstOrCreate(['title' => 'All Products', 'type' => 'pure-grid'], [
            'items' => [], 'order' => ((int) HomeSection::query()->max('order')) + 1,
            'data' => ['layout' => 'all-products', 'source' => 'available', 'rows' => 2, 'cols' => 5,
                'showcase_heading' => 'All Products', 'showcase_limit' => 10, 'showcase_batch' => 10,
                'showcase_columns' => 5, 'showcase_filters' => true,
                'showcase_view_all' => true, 'showcase_sort' => 'newest', 'section_enabled' => true,
                'showcase_background' => '#fbf9f5', 'showcase_accent' => '#c89536'],
        ]);
        if ($section->wasRecentlyCreated) {
            $section->seo()->firstOrCreate([]);
        }
        cacheMemo()->forget('homesections');
        cacheInvalidateNamespace('section_products');
        cacheInvalidateNamespace('api_sections');
        if (config('responsecache.enabled', false)) {
            ResponseCache::clear();
        }
        $this->command?->info('Category templates and the bottom All Products section are ready.');
    }
}
