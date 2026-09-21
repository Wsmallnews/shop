<?php

use Illuminate\Support\Facades\Route;
use RalphJSmit\Livewire\Urls\Middleware\LivewireUrlsMiddleware;
use Wsmallnews\Member\Http\Middleware\ResolveMember;
use Wsmallnews\Shop\Livewire\Auth\ConfirmPassword;
use Wsmallnews\Shop\Livewire\Auth\ForgotPassword;
use Wsmallnews\Shop\Livewire\Auth\Login;
use Wsmallnews\Shop\Livewire\Auth\Register;
use Wsmallnews\Shop\Livewire\Auth\ResetPassword;
use Wsmallnews\Shop\Livewire\Auth\VerifyEmail;
use Wsmallnews\Shop\Livewire\Index;
use Wsmallnews\Shop\Livewire\Order\Confirm;
use Wsmallnews\Shop\Livewire\Pay\Cashier;
use Wsmallnews\Shop\Livewire\Product\Detail as ProductDetail;
use Wsmallnews\Shop\Livewire\Profile;
use Wsmallnews\Shop\Livewire\Profile\Views;
use Wsmallnews\Shop\Livewire\Search;
use Wsmallnews\Shop\Livewire\Settings\Password as SettingsPassword;
use Wsmallnews\Shop\Livewire\Settings\Profile as SettingsProfile;
use Wsmallnews\Shop\Livewire\Settings\TwoFactor;
use Wsmallnews\Shop\ShopPlugin;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Http\Middleware\IdentifyTenant;
use Wsmallnews\Support\Support\Utils as SupportUtils;
use Wsmallnews\User\Http\Controllers\Auth\VerifyEmailController;

$middlewares = Utils::getConfig('routes.middleware') ?? ['web'];
$guard = Utils::getConfig('guard', 'web');
SupportUtils::isTenancyEnabled() && array_unshift($middlewares, IdentifyTenant::class);

// 用户可用性校验
$middlewares[] = 'user-active:' . $guard;

if (Utils::getConfig('auth_user_type', 'member') === 'member') {
    // 解析当前 Member 到请求上下文（传入商城的 guard）
    $middlewares[] = ResolveMember::class . ':' . $guard;
}

// 记录路由历史
$middlewares[] = LivewireUrlsMiddleware::class;

// 首屏初始化页面 SEO 上下文（模块归属由路由声明，seo-init 中间件在 support 包注册）
$middlewares[] = 'seo-init:' . app(ShopPlugin::class)->getId();

Route::domain(Utils::getConfig('routes.domain'))
    ->middleware($middlewares)
    ->prefix(Utils::getConfig('routes.prefix', 'shop'))
    ->name(Utils::getConfig('routes.name', 'sn-shop.'))
    ->group(function () use ($guard) {
        // 不登录api
        Route::middleware('shop-guest:' . $guard)->group(function () {
            Route::get(Utils::getConfig('routes.uri.login', 'login'), Login::class)->name('login');
            Route::get(Utils::getConfig('routes.uri.register', 'register'), Register::class)->name('register');
            Route::get(Utils::getConfig('routes.uri.forgot-password', 'forgot-password'), ForgotPassword::class)->name('forgot.password');
            Route::get(Utils::getConfig('routes.uri.reset-password', 'reset-password/{token}'), ResetPassword::class)->name('reset.password');
        });

        Route::middleware('shop-auth:' . $guard)->group(function () {
            // 验证邮箱
            Route::get(Utils::getConfig('routes.uri.verify-email', 'verify-email'), VerifyEmail::class)->name('verify.email');
            Route::get(Utils::getConfig('routes.uri.verify-email-verification', 'verify-email/{id}/{hash}'), VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verify.email.verification');

            // 确认密码页，需要验证的页面，添加如下中间件：->middleware(['shop-password.confirm'])
            Route::get(Utils::getConfig('routes.uri.password-confirm', 'password-confirm'), ConfirmPassword::class)->name('password.confirm');

            // 个人中心
            Route::get(Utils::getConfig('routes.uri.profile', 'profile'), Profile::class)->name('profile');
            // 浏览记录
            Route::get(Utils::getConfig('routes.uri.profile-views', 'profile/views'), Views::class)->name('profile.views');

            // 个人设置
            Route::get(Utils::getConfig('routes.uri.settings-profile', 'settings/profile'), SettingsProfile::class)->name('settings.profile');
            Route::get(Utils::getConfig('routes.uri.settings-password', 'settings/password'), SettingsPassword::class)->name('settings.password');

            if (Utils::getConfig('two_factor.enabled', false)) {
                // 双因素身份验证
                Route::middleware(['shop-email.verified', 'shop-password.confirm'])->group(function () {
                    Route::get(Utils::getConfig('routes.uri.settings-two-factor', 'settings/two-factor'), TwoFactor::class)->name('settings.two-factor');
                });
            }

            // 商城交易页（需登录）
            Route::get(Utils::getConfig('routes.uri.order-confirm', 'order-confirm'), Confirm::class)->name('order.confirm');
            Route::get(Utils::getConfig('routes.uri.pay-cashier', 'pay-cashier'), Cashier::class)->name('pay.cashier');
        });

        // 普通用户路由
        Route::get(Utils::getConfig('routes.uri.index', '/'), Index::class)->name('index');
        Route::get(Utils::getConfig('routes.uri.product-detail', 'product-detail/{id}'), ProductDetail::class)->name('product.detail');

        if (Utils::getConfig('search.enabled', false) && Utils::getConfig('search.display', 'dropdown') === 'page') {
            // 搜索结果页（页面地址由 routes.uri.search 配置；回车跳转地址经 Search::page 注册）
            Route::get(Utils::getConfig('routes.uri.search', 'search'), Search::class)->name('search');
        }
    });
