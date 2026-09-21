<?php

namespace Wsmallnews\Shop\Livewire\Auth;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Login extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.auth.login'))->robots('noindex');

        return view('sn-shop::livewire.auth.login', [
        ])->layout(Utils::getLayout());
    }
}
