<?php

namespace Wsmallnews\Shop\Livewire\Auth;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class ResetPassword extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.auth.reset_password'))->robots('noindex');

        return view('sn-shop::livewire.auth.reset-password', [
        ])->layout(Utils::getLayout());
    }
}
