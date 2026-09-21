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

        $this->order = $this->user->orders()->where('order_sn', $this->orderSn)->first();
    }

    #[On('pay-start')]
    public function payStart($payMethod): void
    {
        $this->setOrder();

        if (is_null($this->order)) {
            $this->error('订单不存在');

            return;
        }

        if (in_array($this->order->status, [Status::Closed])) {
            $this->error('订单已失效');
        }

        if (in_array($this->order->pay_status, [PayStatus::Paid])) {
            $this->error('订单已支付');
        }

        $payManager = app('sn-pay');
        $pay = $payManager->setPayable($this->order);

        $payRecord = $pay->driver($payMethod)->pay();

        if (in_array($payMethod, ['wechat', 'alipay'])) {
            $pay->driver($payMethod)->thirdPrepay($payRecord);
        }
    }

    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.pay_cashier'))->robots('noindex');

        return view('sn-shop::livewire.pay.cashier', [
        ])->layout(Utils::getLayout());
    }
}
