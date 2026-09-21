<?php

// config for Wsmallnews/Shop
return [
    /**
     * Scopeable 实例声明（单一事实源）
     *
     * main 为默认实例（必须存在）；未显式引用实例键的页面/资源均使用 main，
     * 只有需要差异分区的实例才在此声明，并在 panel_register 条目中以
     * 'scopeable' => '实例键' 显式引用。
     */
    'scopeables' => [
        'main' => [
            'scope_type' => 'sn-shop',
            'scope_id' => 0,
        ],
    ],

    /**
     * Custom models（商城自有模型暂缺，order/pay 等由各自包提供；按需在此登记）
     */
    'models' => [],

    /**
     * Panel register
     *
     * global_default 共享默认（非 FQCN 的 string key）会合并到所有条目：
     *   - navigation_group: 所有页面/资源的默认导航组
     *
     * 条目格式：
     *   - 简单 FQCN：ClassName::class（仅合并共享默认）
     *   - 键值对：ClassName::class => ['key' => 'value']（合并共享默认 + 自定义覆盖）
     *   - 配置项键名使用 snake_case（如 navigation_label、navigation_icon）
     */
    'panel_register' => [
        'global_default' => [
            'navigation_group' => 'sn-shop::shop.global_default.navigation_group',
        ],
        'resources' => [],
        'pages' => [],
    ],

    /**
     * auth guard
     */
    'guard' => 'web',

    /**
     * auth_user_type
     *
     * 默认为 wsmallnews/member 模块，可选 wsmallnews/user 模块, 示例值：member | user
     * 如果你使用多租户， 请设置为 member 模块
     */
    'auth_user_type' => 'member',

    /**
     * 2FA 配置
     */
    'two_factor' => [
        /**
         * 是否启用双因素认证
         */
        'enabled' => true,

        /**
         * 在启用双因素认证时，必须确认一次，否则启动失败
         * two_factor_confirmed_at: 记录启用确认时间，如果为null, two_factor_secret， two_factor_recovery_codes 会被清空，用户双因素启用失败
         */
        'confirm' => true,

        /**
         * 验证窗口，单位：分钟
         */
        'window' => 1,
    ],

    /**
     * 文件基础目录，会自动拼接当前年月日 (仅用于 filament 默认上传组件 (Forms\Components\FileUpload))
     */
    'file_directory' => 'sn/shop/',

    /**
     * 全局搜索（产品等商城内容的站内搜索，由 sn-support::components.search 组件承载）
     */
    'search' => [
        /**
         * 是否启用本模块的全局搜索
         */
        'enabled' => true,
    ],

    /**
     * 前台导航（商城导航条）
     *
     * 与 cms 的 navigation 配置节同构：导航数据复用 cms 的 Navigation 模型与组件行为，
     * 展示形态由本节独立配置——商城导航与 cms 导航可以有不同的风格，互不影响。
     * 注意：主行背景由调用方设置——primary 文字为白色，调用处必须提供深色背景（如 sn-primary-bg），否则文字不可见
     */
    'navigation' => [
        // 整体风格：primary = 主题色面板+白字（默认）；minimal = 白底简约，默认黑字、hover/选中转主题色
        'style' => 'primary',

        // PC 主行（lg+）子菜单展开形式：cascade = 级联（一级向下，深层向左/右弹）| accordion = 手风琴
        'desktop_submenu_style' => 'cascade',

        // PC 主行一级导航的展开触发：hover | click（cascade 深层子菜单跟随同一触发；
        // accordion 仅对一级生效，面板内部的手风琴层级固定 click）
        'desktop_submenu_trigger' => 'hover',

        // PC 一级导航 hover/选中形态：flush = 通栏着色（默认）| rounded = 圆角胶囊（上下留呼吸边）
        'desktop_item_style' => 'flush',

        // 仅「hover 级联」生效：父项是否可点击（直达第一个可用叶子）
        'parent_clickable' => true,

        // "更多"下拉（溢出折叠）里的展开形式：accordion（默认，窄面板更稳）| cascade
        'more_submenu_style' => 'accordion',

        // "更多"下拉里 cascade 形式的触发（accordion 时此值忽略）
        'more_submenu_trigger' => 'click',

        // "更多"按钮仅图标（⋯），不显示文字
        'more_icon_only' => true,
    ],

    /**
     * themes（商城不做多主题切换，无 theme 子键，视图为平铺路径）
     */
    'themes' => [
        // 是否启用暗黑模式
        'dark_mode' => true,

        // 默认主题模式
        'default_dark_mode' => 'system',

        // 强制暗黑主题
        'dark_mode_forced' => false,

        // 页面布局（Livewire 页面 ->layout()）
        'layout' => 'sn-shop::components.layouts.app',

        // 页面容器（页面骨架：页头 + 导航条 + 内容 + 页脚）
        'page_container' => 'sn-shop::container.page',
    ],

    /**
     * 商城前端路由
     */
    'routes' => [
        // 是否注册商城前端路由
        'enabled' => true,

        /**
         * 路由域名（按域名区分租户时设置，如 {tenant:slug}.example.com）
         */
        'domain' => null,

        /**
         * 全部商城路由的中间件
         */
        'middleware' => ['web'],

        /**
         * 路由前缀
         */
        'prefix' => 'shop',

        /**
         * 路由名前缀
         */
        'name' => 'sn-shop.',

        /**
         * 各页面默认 uri
         */
        'uri' => [
            'index' => '/',
            'login' => 'login',
            'register' => 'register',
            'forgot-password' => 'forgot-password',
            'reset-password' => 'reset-password/{token}',
            'verify-email' => 'verify-email',
            'verify-email-verification' => 'verify-email/{id}/{hash}',
            'password-confirm' => 'password-confirm',
            'profile' => 'profile',
            'profile-views' => 'profile/views',
            'settings-profile' => 'settings/profile',
            'settings-password' => 'settings/password',
            'settings-two-factor' => 'settings/two-factor',

            'product-detail' => 'product-detail/{id}',
            'order-confirm' => 'order-confirm',
            'pay-cashier' => 'pay-cashier',

            'search' => 'search',
        ],
    ],

    'media' => [
        'fallback' => [
            'url' => env('SHOP_FALLBACK_IMAGE_URL', null),
            'path' => env('SHOP_FALLBACK_IMAGE_PATH', null),
        ],
    ],
];
