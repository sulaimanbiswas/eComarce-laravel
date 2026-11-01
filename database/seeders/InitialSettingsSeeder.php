<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class InitialSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InitialSetupSeeder::ensureLocalEnvironment();

        foreach ($this->defaults() as $name => $defaultValue) {
            $setting = Setting::query()->firstOrNew(['name' => $name]);
            $existingValue = $setting->exists ? json_decode(json_encode($setting->value, JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR) : null;
            $value = is_array($defaultValue) && ! array_is_list($defaultValue) && is_array($existingValue)
                ? array_replace_recursive($defaultValue, $existingValue)
                : ($existingValue ?? $defaultValue);

            $value = $this->replaceLegacyLabels($value);

            $setting->value = $value;
            if (! $setting->exists || $setting->isDirty('value')) {
                $setting->save();
            }
        }
    }

    private function replaceLegacyLabels(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn (mixed $item): mixed => $this->replaceLegacyLabels($item), $value);
        }

        return match ($value) {
            'Local Commerce' => 'Shapla Mart',
            'Local Admin' => 'Store Manager',
            'Your local demo store' => 'Everyday essentials, delivered with care.',
            'Welcome to Local Commerce' => 'Welcome to Shapla Mart',
            'A local demonstration store.' => 'Discover everyday essentials and useful tools at Shapla Mart.',
            'Browse our demo collection.' => 'Find something useful for your next project.',
            'Local Support' => 'Customer Support',
            'Welcome to your local demo store.' => 'Welcome to Shapla Mart. Explore our latest arrivals.',
            default => $value,
        };
    }

    /** @return array<string, mixed> */
    private function defaults(): array
    {
        $colors = [];
        foreach (['topbar', 'header', 'search', 'navbar', 'category_menu', 'section', 'badge', 'footer', 'primary', 'add_to_cart', 'order_now'] as $section) {
            $colors[$section] = ['background_color' => '#6f42c1', 'background_hover' => '#59359a', 'text_color' => '#ffffff', 'text_hover' => '#ffffff'];
        }

        return [
            'company' => [
                'name' => 'Shapla Mart', 'contact_name' => 'Store Manager', 'email' => 'hello@example.com',
                'phone' => '01700000000', 'whatsapp' => '01700000000', 'address' => 'Dhaka, Bangladesh',
                'tagline' => 'Everyday essentials, delivered with care.', 'office_time' => '9 AM - 6 PM', 'messenger' => '',
                'gmap_ecode' => '', 'dev_name' => '', 'dev_link' => '', 'home_heading' => 'Welcome to Shapla Mart',
                'seo_title' => 'Shapla Mart', 'meta_description' => 'Discover everyday essentials and useful tools at Shapla Mart.',
            ],
            'logo' => ['desktop' => 'strokya/images/logos/logo-1.png', 'mobile' => 'strokya/images/logos/logo-1.png', 'favicon' => ''],
            'show_option' => [
                'customer_login' => true, 'category_dropdown' => true, 'category_carousel' => true,
                'topbar_phone' => true, 'track_order' => true, 'guest_can_see_price' => true,
                'product_grid_button' => 'add_to_cart', 'product_detail_add_to_cart' => true,
                'product_detail_order_now' => true, 'product_detail_buttons_inline' => true,
                'add_to_cart_text' => 'Add To Cart', 'add_to_cart_icon' => '<i class="fas fa-shopping-cart"></i>',
                'order_now_text' => 'Buy Now', 'order_now_icon' => '<i class="fas fa-shopping-cart"></i>',
                'checkout_button_text' => 'Place Order', 'hide_phone_prefix' => false, 'product_sort' => 'created_at',
            ],
            'delivery_areas' => [
                ['name' => 'Inside Dhaka', 'cost' => 60, 'is_default' => true],
                ['name' => 'Outside Dhaka', 'cost' => 120, 'is_default' => false],
            ],
            'delivery_charge' => ['inside_dhaka' => 60, 'outside_dhaka' => 120],
            'default_area' => ['inside' => true, 'outside' => false],
            'delivery_text' => 'Delivery charges are shown at checkout.',
            'free_delivery' => ['enabled' => false, 'for_all' => false, 'products' => []],
            'fraud' => ['max_qty_per_product' => 3, 'allow_per_hour' => 3, 'allow_per_day' => 7],
            'products_page' => ['rows' => 3, 'cols' => 4],
            'related_products' => ['enabled' => true, 'rows' => 1, 'cols' => 4],
            'services' => [
                'enabled' => true,
                'one' => ['title' => 'Quality Products', 'detail' => 'Find something useful for your next project.'],
                'two' => ['title' => 'Customer Support', 'detail' => 'Contact the shop during office hours.'],
                'three' => ['title' => 'Delivery Options', 'detail' => 'Choose your delivery area at checkout.'],
                'four' => ['title' => 'Simple Checkout', 'detail' => 'Place an order in a few steps.'],
            ],
            'color' => $colors,
            'social' => [], 'call_for_order' => 'Call us to place an order.',
            'scroll_text' => 'Welcome to Shapla Mart. Explore our latest arrivals.', 'discount_text' => 'Save [percent]%',
        ];
    }
}
