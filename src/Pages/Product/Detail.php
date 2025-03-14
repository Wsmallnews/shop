<?php

namespace Wsmallnews\Shop\Pages\Product;

use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Wsmallnews\Shop\Pages\Base;

class Detail extends Base
{
    public $id = 0;

    public function mount() {}

    public function render()
    {
        return view('sn-shop::livewire.product.detail', [])->title('产品详情');
    }
}
