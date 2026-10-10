<?php

namespace Wsmallnews\Shop\Livewire\Product;

use Livewire\Attributes\On;
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

    /**
     * 产品组件发起购买（product 包详情组件派发）→ 携 relate_items 跳转确认订单页。
     *
     * 宿主跳转响应用 PHP #[On] + redirect（Livewire 原生），不用页面内联 Alpine——
     * SPA（wire:navigate）跳转经 morph 更新页面，内联脚本一律不执行。
     */
    #[On('sn-product-buy')]
    public function gotoConfirm(string $type, string $from, string $relate_items): void
    {
        $this->redirect(Utils::route('order.confirm') . '?' . http_build_query([
            'type' => $type,
            'from' => $from,
            'relate_items' => $relate_items,
        ]));
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
