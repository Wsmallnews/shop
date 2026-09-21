<?php

namespace Wsmallnews\Shop\Livewire\Product;

use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Detail extends Base
{
    public int $id = 0;

    public function mount(int $id): void
    {
        $this->id = $id;
    }

    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.product_detail'));

        return view('sn-shop::livewire.product.detail', [
        ])->layout(Utils::getLayout());
    }
}
