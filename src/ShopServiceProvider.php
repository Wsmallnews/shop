<?php

namespace Wsmallnews\Shop;

use App\Models\User as UserModel;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wsmallnews\Category\Category;
use Wsmallnews\Category\Resources\Pages\Category as CategoryPage;
use Wsmallnews\Pay\PayManager;
use Wsmallnews\Product\Product;
use Wsmallnews\Product\Resources\AttributeRepositoryResource;
use Wsmallnews\Product\Resources\ProductResource;
use Wsmallnews\Product\Resources\UnitRepositoryResource;
use Wsmallnews\Shop\Commands\ShopCommand;
use Wsmallnews\Shop\Components\Navigation;
use Wsmallnews\Shop\Pages\Index;
use Wsmallnews\Shop\Pages\Pay\Cashier;
use Wsmallnews\Shop\Pages\Product\Detail as ProductDetail;
use Wsmallnews\Shop\Testing\TestsShop;
use Wsmallnews\User\User;

class ShopServiceProvider extends PackageServiceProvider
{
    public static string $name = 'sn-shop';

    public static string $viewNamespace = 'sn-shop';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('wsmallnews/shop');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../routes'))) {
            $package->hasRoutes($this->getRoutes());
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        // 支付实例
        // $this->app->singleton('sn-shop-pay', function ($app) {
        //     return (new PayManager($app))->setPayConfig(function () {
        //         return new ShopPayConfig();     // 这里的实例化参数怎么传
        //     });
        // });

        Product::setResources([
            'group_info' => [
                // 'navigation_parent_item' => '商城',
                'navigation_group' => '产品管理',
            ],
            'resources' => [
                ProductResource::class => [
                    'navigation_label' => '产品库',
                    'navigation_icon' => 'elemplus-goods-filled',
                    'navigation_sort' => 111,
                    'model_label' => '产品',
                    'plural_model_label' => '产品库',
                    'record_title_attribute' => 'title',
                    'slug' => '/products',
                ],
                AttributeRepositoryResource::class => [
                    'navigation_label' => '属性库',
                    'navigation_sort' => 222,
                    'model_label' => '属性',
                    'slug' => '/products/attribute-repositories',
                ],
                UnitRepositoryResource::class => [
                    'navigation_label' => '单位库',
                    'navigation_sort' => 333,
                    'model_label' => '单位',
                    'slug' => '/products/unit-repositories',
                ],
            ],
        ]);

        Category::setPages([
            'group_info' => [
                // 'navigation_parent_item' => '商城',
                'navigation_group' => '产品管理',
            ],
            'pages' => [
                CategoryPage::class => [
                    'navigation_label' => '分类管理',
                    'slug' => 'category/{scope_type}',
                ],
            ],
        ]);

    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/shop/{$file->getFilename()}"),
                ], 'shop-stubs');
            }
        }

        // 用户模块相关
        User::$userModel = UserModel::class;

        // 注册用户模块路由
        User::$routeNames = [
            'index' => 'sn-shop.index',
            'login' => 'sn-shop.login',
            'register' => 'sn-shop.register',
        ];

        Livewire::component('sn-shop-index', Index::class);

        Livewire::component('sn-shop-product-detail', ProductDetail::class);

        Livewire::component('sn-shop-pay-cashier', Cashier::class);

        Livewire::component('sn-shop-navigation', Navigation::class);

        // @sn todo 需要完善的功能
        // *. 需要注册设置页面 | 使用迁移，设置默认值 | 设置在正式上需要缓存
        //

        // Testing
        Testable::mixin(new TestsShop);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'wsmallnews/shop';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('shop', __DIR__ . '/../resources/dist/components/shop.js'),
            // Css::make('shop-styles', __DIR__ . '/../resources/dist/shop.css'),
            // Js::make('shop-scripts', __DIR__ . '/../resources/dist/shop.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            ShopCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return ['web'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            // 'create_shop_table',
        ];
    }
}
