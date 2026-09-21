<?php

namespace Wsmallnews\Shop\Livewire\Auth;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class VerifyEmail extends Base
{
    public string $type = 'check';

    public function mount()
    {
        $this->type = request()->query('type', 'check');
    }

    public function render()
    {
        Seo::title(__('sn-shop::shop.auth.verify_email'))->robots('noindex');

        return view('sn-shop::livewire.auth.verify-email', [
        ])->layout(Utils::getLayout());
    }
}
