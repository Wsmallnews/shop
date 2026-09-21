<?php

namespace Wsmallnews\Shop\Livewire\Auth;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class ConfirmPassword extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.auth.confirm_password'))->robots('noindex');

        return view('sn-shop::livewire.auth.confirm-password', [
        ])->layout(Utils::getLayout());
    }
}
