<?php

namespace Wsmallnews\Shop;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wsmallnews\Product\Enums\ProductStatus;
use Wsmallnews\Product\Support\Utils as ProductUtils;
use Wsmallnews\Shop\Commands\ShopInstall;
use Wsmallnews\Shop\Livewire\Index;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Search as SearchFacade;
use Wsmallnews\Support\Facades\Seo;
use Wsmallnews\Support\Modules\Module;
use Wsmallnews\Support\Modules\ModuleRegistry;
use Wsmallnews\User\Facades\SidebarMenuRegistry as SidebarMenuRegistryFacade;
use Wsmallnews\User\Facades\UserConfig;
use Wsmallnews\User\Http\Middleware\Authenticate;
use Wsmallnews\User\Http\Middleware\EnsureEmailIsVerified;
use Wsmallnews\User\Http\Middleware\RedirectIfAuthenticated;
use Wsmallnews\User\Http\Middleware\RequirePassword;

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
            ->hasConfigFile()
            ->hasMigrations($this->getMigrations())
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);

        if (Utils::getConfig('routes.enabled') !== false) {     // 只要不等于 false 就注册路由
            $package->hasRoutes($this->getRoutes());
        }
    }

    public function packageRegistered(): void
    {
        // 模块身份登记（ModuleRegistry 单一事实源：类反查/存在性校验/插件实例）
        ModuleRegistry::register(new Module(
            id: static::$name,
            namespace: 'Wsmallnews\\Shop',
            plugin: ShopPlugin::class,
        ));
    }

    public function packageBooted(): void
    {
        // 定义中间件别名
        $this->app['router']->aliasMiddleware('shop-auth', Authenticate::class);
        $this->app['router']->aliasMiddleware('shop-guest', RedirectIfAuthenticated::class);
        $this->app['router']->aliasMiddleware('shop-password.confirm', RequirePassword::class);
        $this->app['router']->aliasMiddleware('shop-email.verified', EnsureEmailIsVerified::class);

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

        // 注册 livewire 命名空间（自动发现 src/Livewire/ 下的组件）
        Livewire::addNamespace(
            namespace: 'sn-shop',
            classNamespace: 'Wsmallnews\\Shop\\Livewire'
        );

        // 路由处理器反向查找注册（Route::get(Xxx::class) 需要类名→别名映射）
        Livewire::component('sn-shop::index', Index::class);

        // 注册用户认证配置（商城模块的登录注册跳转地址）
        UserConfig::config(app(ShopPlugin::class)->getId(), function () {
            return [
                'guard' => Utils::getConfig('guard', 'web'),
                'two_factor' => Utils::getConfig('two_factor', []),
                'urls' => [
                    'index' => Utils::route('index'),
                    'login' => Utils::route('login'),
                    'register' => Utils::route('register'),
                    'profile' => Utils::route('profile'),
                    'forgot-password' => Utils::route('forgot.password'),
                    'reset-password' => fn ($params) => Utils::route('reset.password', $params),
                    'verify-email' => Utils::route('verify.email'),
                    'verify-email-verification' => function ($parameters) {
                        if (! isset($parameters['tenant'])) {        // 没有租户参数,则添加租户参数
                            $parameters['tenant'] = current_tenant();
                        }

                        $parameters['module'] = app(ShopPlugin::class)->getId();

                        return URL::temporarySignedRoute(
                            Utils::getConfig('routes.name', '') . 'verify.email.verification',
                            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                            $parameters
                        );
                    },
                    'password-confirm' => Utils::route('password.confirm'),
                ],
            ];
        });

        // 注册全局搜索（模块配置 search.enabled 关闭时不注册来源，前端也不渲染搜索框）
        if (Utils::getConfig('search.enabled', true)) {
            // sn-shop.search 整节透传到搜索注册表：模块想覆盖哪些 search 配置就在配置节写哪些键
            // （可覆盖键清单见 support 包 config/sn-support.php 的 search 节），未写的键走全局兜底
            $searchConfig = collect(Utils::getConfig('search', []))->except('enabled')->all();
            $searchConfig['page'] ??= fn (?string $query) => Utils::route('search', ['q' => $query]);

            SearchFacade::config(app(ShopPlugin::class)->getId(), $searchConfig)
                ->registers(app(ShopPlugin::class)->getId(), [
                    [
                        'key' => 'product',
                        'model' => ProductUtils::getProductModel(),
                        'group' => __('sn-shop::shop.product_resource.model_label'),
                        // 可售状态：上架 + 隐藏（隐藏 = 不列表展示，直达链接可买）
                        'query' => fn ($query) => $query->whereIn('status', [ProductStatus::Up, ProductStatus::Hidden]),
                        'scopeable' => Utils::getScopeable(),
                        'url' => fn ($record) => Utils::route('product.detail', ['id' => $record->id]),
                    ],
                ]);
        }

        // 注册 SEO 模块默认值（模块名 = 插件 ID，与其他模块互不覆盖）：
        // 闭包在每次渲染时才解析 Settings，自动跟随当前租户（多租户下 GeneralSettings 走 team_database 仓库，每租户一份）
        // Seo::config(app(ShopPlugin::class)->getId(), function (): array {
        //     $general = app(GeneralSettings::class);

        //     return [
        //         'site_name' => filled($general->site_name) ? $general->site_name : config('app.name'),
        //         'description' => filled($general->seo_description) ? $general->seo_description : $general->site_slogan,
        //         'image' => filled($general->default_og_image) ? files_url($general->default_og_image) : null,
        //         'favicon' => filled($general->favicon) ? files_url($general->favicon) : null,
        //         'analytics_code' => $general->analytics_code,
        //     ];
        // });

        // 注册用户侧边栏菜单
        SidebarMenuRegistryFacade::registers(app(ShopPlugin::class)->getId(), [
            fn () => [
                'key' => 'profile',
                'label' => __('sn-shop::shop.sidebar.profile'),
                'url' => Utils::route('profile'),
                'icon' => Heroicon::OutlinedUser,
                'active_icon' => Heroicon::User,
            ],
            fn () => [
                'key' => 'profile-views',
                'label' => __('sn-shop::shop.sidebar.profile_views'),
                'url' => Utils::route('profile.views'),
                'icon' => Heroicon::OutlinedEye,
                'active_icon' => Heroicon::Eye,
            ],
            fn () => [
                'key' => 'settings-profile',
                'label' => __('sn-shop::shop.sidebar.settings_profile'),
                'url' => Utils::route('settings.profile'),
                'icon' => Heroicon::OutlinedPencilSquare,
                'active_icon' => Heroicon::PencilSquare,
            ],
            fn () => [
                'key' => 'settings-password',
                'label' => __('sn-shop::shop.sidebar.settings_password'),
                'url' => Utils::route('settings.password'),
                'icon' => Heroicon::OutlinedLockClosed,
                'active_icon' => Heroicon::LockClosed,
            ],
            fn () => [
                'key' => 'settings-two-factor',
                'label' => __('sn-shop::shop.sidebar.settings_two_factor'),
                'url' => fn () => Utils::route('settings.two-factor'),
                'icon' => Heroicon::OutlinedKey,
                'active_icon' => Heroicon::Key,
                'hidden' => fn () => ! Utils::getConfig('two_factor.enabled', false),
            ],
        ]);
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
            // AlpineComponent::make('cms', __DIR__ . '/../resources/dist/components/cms.js'),
            // Css::make('cms-styles', __DIR__ . '/../resources/dist/shop.css'),
            // Js::make('cms-scripts', __DIR__ . '/../resources/dist/shop.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            ShopInstall::class,
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
        return [];
    }
}
