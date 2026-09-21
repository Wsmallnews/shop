<?php

namespace Wsmallnews\Shop\Livewire\Settings;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class TwoFactor extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.sidebar.settings_two_factor'))->robots('noindex');
        $breadcrumbs = [
            ['label' => __('sn-shop::shop.sidebar.profile'), 'url' => Utils::route('profile')],
            ['label' => __('sn-shop::shop.sidebar.settings_two_factor'), 'url' => Utils::route('settings.two-factor')],
        ];

        return view('sn-shop::livewire.settings.two-factor', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(Utils::getLayout());
    }
}
