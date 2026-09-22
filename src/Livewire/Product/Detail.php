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
        // 产品标题等 SEO 由内嵌的产品组件按产品数据设置，这里只兜底页面标题
        Seo::title(__('sn-shop::shop.frontend.product_detail'));

        return view('sn-shop::livewire.product.detail', [
            'authUser' => Utils::getAuthUser(),
        ])->layout(Utils::getLayout());
    }
}
