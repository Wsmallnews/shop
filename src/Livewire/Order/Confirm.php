<?php

namespace Wsmallnews\Shop\Livewire\Order;

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Confirm extends Base
{
    public string $type = 'product';

    public array $relateItems = [];

    public string $from = 'product-detail';

    public ?Model $user;

    public function mount(): void
    {
        $this->user = Utils::getAuthUser();

        $this->type = request()->get('type', $this->type);

        $relateItems = request()->get('relate_items', $this->relateItems);
        $this->relateItems = is_array($relateItems) ? $relateItems : json_decode($relateItems, true);

        $this->from = request()->get('from', $this->from);
    }

    /**
     * 订单创建完成（order 包 Confirm 组件派发）→ 携单号跳转收银台。
     *
     * 宿主对「结果事实」事件的跳转响应用 PHP #[On] + redirect（Livewire 原生），
     * 不用页面内联 Alpine 监听——SPA（wire:navigate）跳转经 morph 更新页面，
     * 内联脚本一律不执行（F1 完整形态，规则见 component-events.md）。
     */
    #[On('sn-order-created')]
    public function gotoCashier(string $order_sn): void
    {
        $this->redirect(Utils::route('pay.cashier') . '?order_sn=' . $order_sn);
    }

    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.order_confirm'))->robots('noindex');

        return view('sn-shop::livewire.order.confirm', [
        ])->layout(Utils::getLayout());
    }
}
