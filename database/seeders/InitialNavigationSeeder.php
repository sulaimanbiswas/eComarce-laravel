<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class InitialNavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InitialSetupSeeder::ensureLocalEnvironment();

        foreach (['about-us' => 'About Us', 'contact' => 'Contact', 'privacy-policy' => 'Privacy Policy', 'terms-and-conditions' => 'Terms and Conditions'] as $slug => $title) {
            Page::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'content' => '<p>Local demo content. Replace this page with your own information before publishing.</p>',
            ]);
        }

        $menus = [
            'header-menu' => ['name' => 'Header Menu', 'items' => ['/' => 'Home', '/shop' => 'Shop', '/categories' => 'Categories', '/brands' => 'Brands']],
            'topbar-menu' => ['name' => 'Topbar Menu', 'items' => ['/track-order' => 'Track Order', '/contact' => 'Contact']],
            'quick-links' => ['name' => 'Quick Links', 'items' => ['/about-us' => 'About Us', '/contact' => 'Contact', '/privacy-policy' => 'Privacy Policy', '/terms-and-conditions' => 'Terms and Conditions']],
        ];

        foreach ($menus as $slug => $data) {
            $menu = Menu::query()->firstOrCreate(['slug' => $slug], ['name' => $data['name']]);
            $order = (int) $menu->menuItems()->max('order');
            foreach ($data['items'] as $href => $name) {
                $menu->menuItems()->firstOrCreate(['href' => $href], ['name' => $name, 'order' => ++$order]);
            }
        }
    }
}
