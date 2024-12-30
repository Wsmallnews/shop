<?php

namespace Wsmallnews\Shop\Pages\Product;

use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Wsmallnews\Shop\Pages\Base;

class Detail extends Base
{
    public $id = 0;

    public function mount() {}

    // #[On('product-buy')]
    // public function buy($params)
    // {
    //     return "这个是 php 的监听";
    //     // $redirectData = $params['data'] ?? [];

    //     // return $this->redirect('/shop/order-confirm?' . Arr::query($redirectData));
    // }

    // public function jsBuy($params)
    // {
    //     $redirectData = $params['data'] ?? [];

    //     return $this->redirect('/shop/order-confirm?' . Arr::query($redirectData));
    // }

    public function render()
    {
        return view('sn-shop::livewire.product.detail', [])->title('产品详情');
    }
}
