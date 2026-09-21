<?php

namespace Wsmallnews\Shop\Livewire\Auth;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class ForgotPassword extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.auth.forgot_password'))->robots('noindex');

        return view('sn-shop::livewire.auth.forgot-password', [
        ])->layout(Utils::getLayout());
    }
}
