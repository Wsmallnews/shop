<?php

namespace Wsmallnews\Shop\Livewire;

use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Profile extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.profile'))->robots('noindex');
        $breadcrumbs = [
            ['label' => __('sn-shop::shop.frontend.profile'), 'url' => Utils::route('profile')],
        ];

        return view('sn-shop::livewire.profile', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(Utils::getLayout());
    }
}
