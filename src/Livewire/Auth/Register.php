<?php

namespace Wsmallnews\Shop\Livewire\Auth;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Register extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.auth.register'))->robots('noindex');

        return view('sn-shop::livewire.auth.register', [
        ])->layout(Utils::getLayout());
    }
}
