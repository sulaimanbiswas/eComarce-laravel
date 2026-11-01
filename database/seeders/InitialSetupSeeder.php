<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Services\AccountingService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use LogicException;
use Spatie\ResponseCache\Facades\ResponseCache;

class InitialSetupSeeder extends Seeder
{
    use WithoutModelEvents;

    public const PASSWORD = 'Password123!';

    public static function ensureLocalEnvironment(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Initial setup contains demo accounts and can only run in local or testing environments.');
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        self::ensureLocalEnvironment();

        (new Admin)->getConnection()->transaction(function (): void {
            $this->call([
                InitialSettingsSeeder::class,
                LocalAccountsSeeder::class,
                SampleCatalogSeeder::class,
                InitialNavigationSeeder::class,
            ]);

            app(AccountingService::class)->seedDefaultAccounts();
        });

        $cacheKeys = ['settings', 'slides', 'brands', 'brands:carousel', 'categories:carousel', 'categories:nested:enabled', 'categories:nested:all', 'homesections', 'menus:header-menu', 'menus:topbar-menu', 'menus:quick-links'];
        foreach (range(0, 15) as $count) {
            $cacheKeys[] = 'categories:nested:'.$count;
        }
        foreach ($cacheKeys as $key) {
            cacheMemo()->forget($key);
            Cache::forget($key);
        }
        foreach (['api_categories', 'api_category', 'api_sections', 'product_filters', 'section_products', 'related_products'] as $namespace) {
            cacheInvalidateNamespace($namespace);
        }
        if (config('responsecache.enabled', false)) {
            ResponseCache::clear();
        }

        $this->command?->info('Initial setup is ready. New demo accounts use Password123! Existing business records are preserved.');
        if (config('local_setup.reset_passwords', false)) {
            $this->command?->info('The seeded demo email addresses also had their existing passwords reset to Password123!.');
        } else {
            $this->command?->info('Existing account passwords are preserved. Use setup:local --reset-passwords to reset only the demo accounts.');
        }
        $this->command?->info('Staff login: /getpass. Reseller login requires APP_ONINDA=true and APP_RESELL=true. Investor login requires INVESTMENT_ENABLED=true.');
    }
}
