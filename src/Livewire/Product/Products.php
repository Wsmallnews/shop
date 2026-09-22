<?php

namespace Wsmallnews\Shop\Livewire\Product;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Products extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.products'));

        return view('sn-shop::livewire.product.products', [
        ])->layout(Utils::getLayout());
    }
}
