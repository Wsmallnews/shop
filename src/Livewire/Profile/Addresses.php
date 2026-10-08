<?php

namespace Wsmallnews\Shop\Livewire\Profile;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

/**
 * 个人中心 - 收货地址管理页（页面壳，地址簿组件来自 profile 包）
 */
class Addresses extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.sidebar.profile_addresses'))->robots('noindex');
        $breadcrumbs = [
            ['label' => __('sn-shop::shop.sidebar.profile'), 'url' => Utils::route('profile')],
            ['label' => __('sn-shop::shop.sidebar.profile_addresses'), 'url' => Utils::route('profile.addresses')],
        ];

        return view('sn-shop::livewire.profile.addresses', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(Utils::getLayout());
    }
}
