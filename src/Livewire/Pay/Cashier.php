<?php

namespace Wsmallnews\Shop\Livewire\Pay;

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Wsmallnews\Order\Enums\Order\PayStatus;
use Wsmallnews\Order\Enums\Order\Status;
use Wsmallnews\Order\Models\Order;
use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Cashier extends Base
{
    public ?string $orderSn;

    public ?Model $user;

    public ?Order $order;

    public function mount(): void
    {
        $this->user = Utils::getAuthUser();

        $this->orderSn = request()->get('order_sn', '');

        $this->setOrder();
    }

    public function setOrder(): void
    {
        if (is_null($this->user) || blank($this->orderSn)) {
            $this->order = null;

            return;
        }

        // 买家维度查单（buyer 多态；orders() 关联随阶段 C buyer 契约落地）
        $this->order = Order::query()
            ->where('buyer_type', $this->user->getMorphClass())
            ->where('buyer_id', $this->user->getKey())
            ->where('order_sn', $this->orderSn)
            ->first();
    }

    #[On('pay-start')]
    public function payStart(string $channel, string $method): void
    {
        $this->setOrder();

        if (is_null($this->order)) {
            $this->error('订单不存在');

            return;
        }

        if ($this->order->status === Status::Closed) {
            $this->error('订单已失效');

            return;
        }

        if ($this->order->pay_status === PayStatus::Paid) {
            $this->error('订单已支付');

            return;
        }

        // 发起支付：channel + method 定位渠道操作器；金额默认剩余应付（分批/定金可传指定金额）
        $payResult = app('sn-pay')
            ->payer($this->user)
            ->payable($this->order)
            ->channel($channel, $method)
            ->pay();

        if ($payResult->isPaid()) {
            // 余额类支付直接完成
            $this->dispatch('pay-finish', ['pay_sn' => $payResult->payRecord->pay_sn]);

            return;
        }

        // 第三方预下单结果（二维码/唤起参数/跳转），前端按 method 消费
        $this->dispatch('pay-prepay-result', [
            'pay_sn' => $payResult->payRecord->pay_sn,
            'channel' => $channel,
            'method' => $method,
            'result' => $payResult->sdkResult,
        ]);
    }

    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.pay_cashier'))->robots('noindex');

        return view('sn-shop::livewire.pay.cashier', [
        ])->layout(Utils::getLayout());
    }
}
