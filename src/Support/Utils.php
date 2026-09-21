<?php

declare(strict_types=1);

namespace Wsmallnews\Shop\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Wsmallnews\Member\Models\Member;
use Wsmallnews\Shop\Exceptions\ShopException;
use Wsmallnews\Support\Data\ScopeableContext;
use Wsmallnews\Support\Exceptions\InvalidScopeException;
use Wsmallnews\Support\Support\Utils as SupportUtils;

/**
 * Utility class for Shop package configuration and helpers.
 */
class Utils
{
    /**
     * Get configuration value.
     *
     * @param  string|null  $name  Configuration key (dot notation)
     * @param  mixed  $default  Default value if not found
     */
    public static function getConfig(?string $name = null, mixed $default = null): mixed
    {
        $config = config('sn-shop');

        return $name ? (data_get($config, $name) ?? $default) : $config;
    }

    /**
     * Get scopeable configuration as ScopeableContext object.
     *
     * @param  string|null  $key  实例键（null = main 默认实例）
     *
     * @throws ShopException
     */
    public static function getScopeableContext(?string $key = null): ScopeableContext
    {
        try {
            return SupportUtils::getScopeFromInstances('sn-shop.scopeables', $key);
        } catch (InvalidScopeException $e) {
            throw new ShopException('Scopeable configuration error. ' . $e->getMessage());
        }
    }

    /**
     * Get scopeable array.
     *
     * @param  string|null  $key  实例键（null = main 默认实例）
     * @return array{scope_type: string, scope_id: int}
     *
     * @throws ShopException
     */
    public static function getScopeable(?string $key = null): array
    {
        return self::getScopeableContext($key)->toArray();
    }

    /**
     * Get scope type.
     *
     * @param  string|null  $key  实例键（null = main 默认实例）
     *
     * @throws ShopException
     */
    public static function getScopeType(?string $key = null): string
    {
        return self::getScopeableContext($key)->scopeType;
    }

    /**
     * Get scope ID.
     *
     * @param  string|null  $key  实例键（null = main 默认实例）
     *
     * @throws ShopException
     */
    public static function getScopeId(?string $key = null): int
    {
        return self::getScopeableContext($key)->scopeId;
    }

    /**
     * 当前商城用户端认证用户
     */
    public static function getUser(): ?Model
    {
        return Auth::guard(self::getConfig('guard', 'web'))->user();
    }

    /**
     * 获取当前请求中的 Member（由 ResolveMember 中间件设置）
     */
    public static function getAuthMember(): ?Member
    {
        return current_member();
    }

    /**
     * 获取当前请求中的认证用户（根据 auth_user_type 配置）
     */
    public static function getAuthUser(): ?Model
    {
        return self::getConfig('auth_user_type', 'member') === 'member' ? static::getAuthMember() : static::getUser();
    }

    /**
     * Get panel register raw config.
     *
     * @param  string|null  $type  Register type (pages, resources, global_default) or null for all
     */
    public static function getPanelRegister(?string $type = null): mixed
    {
        if (blank($type)) {
            return self::getConfig('panel_register', null);
        }

        return self::getConfig("panel_register.{$type}", null);
    }

    /**
     * Get model class by name.
     *
     * @param  string  $name  Model name
     * @param  bool  $shouldException  Whether to throw exception if not found
     *
     * @throws ShopException
     */
    public static function getModel(string $name, bool $shouldException = true): ?string
    {
        $model = self::getConfig('models')[$name] ?? null;

        if (blank($model) && $shouldException) {
            throw new ShopException("Model {$name} not found.");
        }

        return $model;
    }

    /**
     * Get file directory path with optional type and date.
     *
     * @param  string|null  $type  Directory type
     */
    public static function getFileDirectory(?string $type = null): string
    {
        return self::getConfig('file_directory', 'sn/shop/') . ($type ? $type . '/' : '') . date('Ymd');
    }

    /**
     * Get default dark mode setting.
     */
    public static function getDefaultDarkMode(): string
    {
        return self::getConfig('themes.default_dark_mode', 'system');
    }

    /**
     * Check if dark mode is enabled.
     */
    public static function hasDarkMode(): bool
    {
        return self::getConfig('themes.dark_mode', false);
    }

    /**
     * Check if dark mode is forced.
     */
    public static function hasDarkModeForced(): bool
    {
        return self::getConfig('themes.dark_mode_forced', false);
    }

    /**
     * Get layout view path.
     */
    public static function getLayout(): string
    {
        return self::getConfig('themes.layout', 'sn-shop::components.layouts.app');
    }

    /**
     * Get page container view path.
     */
    public static function getPageContainer(): string
    {
        return self::getConfig('themes.page_container', 'sn-shop::container.page');
    }

    /**
     * 模块内部路由：自动拼接 routes.name 前缀，并经 sn_route 补充租户参数
     *
     * @param  string  $name  路由名（不含前缀，如 index、pay.cashier）
     */
    public static function route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $name = self::getConfig('routes.name', '') . $name;

        return sn_route($name, $parameters, $absolute);
    }
}
