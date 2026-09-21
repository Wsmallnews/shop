<?php

namespace Wsmallnews\Shop\Livewire\Settings;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Password extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.sidebar.settings_password'))->robots('noindex');
        $breadcrumbs = [
            ['label' => __('sn-shop::shop.sidebar.profile'), 'url' => Utils::route('profile')],
            ['label' => __('sn-shop::shop.sidebar.settings_password'), 'url' => Utils::route('settings.password')],
        ];

        return view('sn-shop::livewire.settings.password', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(Utils::getLayout());
    }
}
