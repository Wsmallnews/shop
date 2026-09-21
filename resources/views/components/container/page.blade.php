@props([
    'scopeType',
    'scopeId',
])

@php
    use Wsmallnews\Cms\Settings\GeneralSettings;
    use Wsmallnews\Shop\ShopPlugin;
    use Wsmallnews\Shop\Support\Utils;

    // 站点身份（站名/logo）复用 cms 的全站设置，商城不重复维护一份
    $general = app(GeneralSettings::class);
    $siteName = filled($general->site_name) ? $general->site_name : config('app.name');
    $logoUrl = filled($general->logo) ? files_url($general->logo) : null;

    // 导航风格：读商城自己的 navigation 配置节（与 cms 导航互不影响）
    $navStyle = Utils::navigationConfig('style', 'primary');
@endphp

<div {{ $attributes->merge(['class' => 'sn-shop-container-page w-full flex flex-col h-dvh']) }}>
    {{-- 页头条：品牌 + 搜索 + 登录注册/个人信息 --}}
    <div class="w-full shrink-0 flex bg-top-right bg-cover h-24 lg:h-28">
        <div class="container mx-auto sn-page-x flex items-center justify-between gap-4">
            {{-- 品牌（logo + 站名，与页脚统一逻辑） --}}
            <x-sn-cms::brand
                :logo-url="$logoUrl"
                :site-name="$siteName"
                :with-name="$general->logo_with_site_name"
                size="header"
            />

            {{-- 搜索框与登录注册/个人信息 合并为一个容器整体靠右；搜索框聚焦时展开 --}}
            <div class="hidden lg:flex items-center justify-end grow gap-6">
                @if (Utils::getConfig('search.enabled', true))
                    <div class="w-56 xl:w-64 focus-within:w-80 transition-[width] duration-300 ease-in-out">
                        <livewire:sn-support::components.search
                            :limit="5"
                            :module="app(ShopPlugin::class)->getId()"
                            :display="Utils::getConfig('search.display')"
                            placeholder="{{ __('sn-shop::shop.frontend.search_placeholder') }}"
                        />
                    </div>
                @endif

                <div class="flex gap-4 shrink-0">
                    @auth(Utils::getConfig('guard', 'web'))
                        <livewire:sn-user::components.user.menu :module="app(ShopPlugin::class)->getId()" switch-dark-mode="{{ Utils::hasDarkMode() && ! Utils::hasDarkModeForced() }}" />
                    @else
                        <x-filament::button tag="a" href="{{ Utils::route('login') }}">
                            {{ __('sn-shop::shop.frontend.login') }}
                        </x-filament::button>
                        <x-filament::button color="gray" tag="a" href="{{ Utils::route('register') }}">
                            {{ __('sn-shop::shop.frontend.register') }}
                        </x-filament::button>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="w-full flex flex-col grow">
        {{-- 导航：直接复用 cms 的导航组件（同一视图与 CSS）——传 module 后配置读 sn-shop.php 的
            navigation 节、路由走 sn-shop.* 前缀，与 cms 导航互不影响；theme-view 是视图覆盖的退路 --}}
        <div @class([
            'w-full',
            'sn-primary-bg' => $navStyle === 'primary',
            'bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700' => $navStyle === 'minimal',
        ])>
            <div class="container mx-auto sn-page-x">
                <livewire:sn-cms::components.navigation.navigation :scope-type="$scopeType" :scope-id="$scopeId" :module="app(ShopPlugin::class)->getId()">
                    {{-- 用户区（移动端菜单底部）：在 shop 的模块语境里生成链接与组件参数 --}}
                    <x-slot:userZone>
                        @auth(Utils::getConfig('guard', 'web'))
                            <livewire:sn-user::components.user.menu :module="app(ShopPlugin::class)->getId()" placement="bottom-start" switch-dark-mode="{{ Utils::hasDarkMode() && ! Utils::hasDarkModeForced() }}" />
                        @else
                            <div class="flex gap-3">
                                <x-filament::button tag="a" href="{{ Utils::route('login') }}" class="flex-1">
                                    {{ __('sn-shop::shop.frontend.login') }}
                                </x-filament::button>
                                <x-filament::button color="gray" tag="a" href="{{ Utils::route('register') }}" class="flex-1">
                                    {{ __('sn-shop::shop.frontend.register') }}
                                </x-filament::button>
                            </div>
                        @endauth
                    </x-slot:userZone>
                </livewire:sn-cms::components.navigation.navigation>
            </div>
        </div>

        {{-- ===== 页面级容器（sn-page 全站唯一，页面视图禁止再写）：宽度对齐 + 页面节奏 --}}
        <div class="w-full @container">
            <div class="sn-page">
                {{ $slot }}
            </div>
        </div>

        {{-- 商城页脚：cms footer 组件为 cms 自用（scopeable 由其调用处传入），shop 后续按需自建 --}}
    </div>
</div>
