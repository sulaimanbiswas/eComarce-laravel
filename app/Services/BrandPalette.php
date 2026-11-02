<?php

namespace App\Services;

class BrandPalette
{
    public function normalize(string $color): string
    {
        if (preg_match('/^#([a-f0-9]{3})$/i', $color, $matches)) {
            return '#'.strtolower(implode('', array_map(fn (string $channel): string => $channel.$channel, str_split($matches[1]))));
        }

        return strtolower($color);
    }

    public function mix(string $color, string $target, float $weight): string
    {
        $color = $this->normalize($color);
        $target = $this->normalize($target);
        $channels = [];

        foreach ([1, 3, 5] as $offset) {
            $channel = hexdec(substr($color, $offset, 2));
            $targetChannel = hexdec(substr($target, $offset, 2));
            $channels[] = (int) round($channel + ($targetChannel - $channel) * $weight);
        }

        return sprintf('#%02x%02x%02x', ...$channels);
    }

    public function foreground(string $background): string
    {
        $background = $this->normalize($background);
        $channels = [];

        foreach ([1, 3, 5] as $offset) {
            $channel = hexdec(substr($background, $offset, 2)) / 255;
            $channels[] = $channel <= 0.04045 ? $channel / 12.92 : (($channel + 0.055) / 1.055) ** 2.4;
        }

        $luminance = $channels[0] * 0.2126 + $channels[1] * 0.7152 + $channels[2] * 0.0722;

        return (1.05 / ($luminance + 0.05)) >= (($luminance + 0.05) / 0.05) ? '#ffffff' : '#000000';
    }

    /**
     * @param  array{primary: string, secondary: string, accent: string}  $palette
     * @return array<string, array{background_color: string, background_hover: string, text_color: string, text_hover: string}>
     */
    public function colors(array $palette): array
    {
        $primary = $this->normalize($palette['primary']);
        $secondary = $this->normalize($palette['secondary']);
        $accent = $this->normalize($palette['accent']);
        $deep = $this->mix($primary, '#000000', 0.2);
        $soft = $this->mix($primary, '#ffffff', 0.95);
        $ink = $this->mix($primary, '#000000', 0.6);

        return [
            'topbar' => $this->surface($deep),
            'header' => $this->surface($primary),
            'navbar' => $this->surface($deep),
            'search' => $this->surface($secondary),
            'category_menu' => ['background_color' => '#ffffff', 'background_hover' => $soft, 'text_color' => $ink, 'text_hover' => $ink],
            'section' => ['background_color' => $soft, 'background_hover' => $soft, 'text_color' => $ink, 'text_hover' => $ink],
            'badge' => $this->surface($accent),
            'footer' => $this->footerColors($primary, $accent),
            'primary' => $this->surface($primary),
            'add_to_cart' => $this->surface($secondary),
            'order_now' => $this->surface($accent),
        ];
    }

    /**
     * @return array{background_color: string, background_hover: string, text_color: string, text_hover: string}
     */
    public function footerColors(string $primary, string $accent): array
    {
        $background = $this->mix($primary, '#101827', 0.86);

        return [
            'background_color' => $background,
            'background_hover' => $background,
            'text_color' => '#f1f5f9',
            'text_hover' => $this->mix($accent, '#ffffff', 0.65),
        ];
    }

    /**
     * @return array{background_color: string, background_hover: string, text_color: string, text_hover: string}
     */
    private function surface(string $background): array
    {
        $hover = $this->mix($background, '#000000', 0.14);

        return [
            'background_color' => $background,
            'background_hover' => $hover,
            'text_color' => $this->foreground($background),
            'text_hover' => $this->foreground($hover),
        ];
    }
}
