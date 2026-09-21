<?php

namespace Wsmallnews\Shop\Livewire\Profile;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Views extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.sidebar.profile_views'))->robots('noindex');
        $breadcrumbs = [
            ['label' => __('sn-shop::shop.sidebar.profile'), 'url' => Utils::route('profile')],
            ['label' => __('sn-shop::shop.sidebar.profile_views'), 'url' => Utils::route('profile.views')],
        ];

        return view('sn-shop::livewire.profile.views', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(Utils::getLayout());
    }
}
