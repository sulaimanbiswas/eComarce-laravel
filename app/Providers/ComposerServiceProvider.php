<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $parameters = Route::current()?->parameters();
            foreach ($parameters ?: [] as $key => $value) {
                if (! $view->offsetExists($key)) {
                    $view->with($key, $value);
                }
            }
        });

        $menus = [
            'topbar-menu' => 'topbar',
            'header-menu' => 'header.menu.*',
            'quick-links' => 'footer-*',
        ];

        foreach ($menus as $slug => $view) {
            View::composer("partials.{$view}", function ($view) use ($slug): void {
                $view->withMenuItems(cacheMemo()->rememberForever('menus:'.$slug, fn () => Menu::whereSlug($slug)->first()?->menuItems ?: new Collection));
            });
        }

        View::composer(['layouts.yellow.master'], function ($view): void {
            $view->with('categories', Category::nested());
        });

        View::composer('partials.header.more', function ($view): void {
            $view->with('extraMenuItems', cacheMemo()->rememberForever('menus:topbar-menu', fn () => Menu::whereSlug('topbar-menu')->first()?->menuItems ?: new Collection));
        });

        View::composer(['partials.trust-services', 'admin.settings.trust-services'], function ($view): void {
            $storedServices = $view->name() === 'admin.settings.trust-services'
                ? old('services', setting('services'))
                : setting('services');
            $serviceSettings = array_replace(config('storefront.trust_defaults'), (array) $storedServices);
            $serviceItems = data_get($storedServices, 'items');

            if ($serviceItems === null) {
                $serviceItems = collect(['one' => 'shield', 'two' => 'headphones', 'three' => 'truck', 'four' => 'return'])
                    ->map(fn ($icon, $key): array => [
                        'title' => data_get($storedServices, $key.'.title', ''),
                        'detail' => data_get($storedServices, $key.'.detail', ''),
                        'icon' => $icon,
                        'enabled' => filled(data_get($storedServices, $key.'.title')),
                        'link' => '',
                    ])->values();
            }

            $serviceItems = collect($serviceItems)->values()->map(fn ($item, $index): array => array_replace([
                'title' => '', 'detail' => '', 'icon' => 'shield', 'enabled' => true, 'link' => '', 'order' => $index,
            ], (array) $item))->sortBy('order')->values();

            if (data_get($storedServices, 'columns') === null) {
                $serviceSettings['columns'] = min(4, max(2, $serviceItems->where('enabled', true)->count()));
            }

            $view->with(compact('serviceSettings', 'serviceItems'));
        });

        $settingsPages = [
            'index',
            'products.index',
            'partials.header.*',
            'partials.footer',
            'products.show',
            'admin.layouts.master',
            'admin.orders.show',
            'admin.orders.invoices',
            'admin.orders.invoices-1',
            'admin.orders.invoices-2',
            'admin.orders.invoices-3',
            'admin.orders.stickers',
            'user.layouts.master',
            'layouts.light.master',
            'layouts.yellow.master',
            'layouts.reseller.master',
            'layouts.errors.master',
        ];
        View::composer($settingsPages, function ($view): void {
            $view->with(Setting::array());
        });
    }
}
