<?php

namespace Wsmallnews\Shop;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Wsmallnews\Category\Resources\Pages\Category;
use Wsmallnews\Product\Resources\AttributeRepositoryResource;
// use Wsmallnews\Product\Resources\ProductResource;
use Wsmallnews\Shop\Filament\Resources\ProductResource;
use Wsmallnews\Product\Resources\UnitRepositoryResource;
use Wsmallnews\Shop\Filament\Pages\Settings\WechatPay;

class ShopPlugin implements Plugin
{
    public function getId(): string
    {
        return 'sn-shop';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                ProductResource::class,
                // AttributeRepositoryResource::class,
                // UnitRepositoryResource::class,
            ])
            ->pages([
                WechatPay::class,
                // Category::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
