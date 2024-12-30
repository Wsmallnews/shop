<?php

namespace Wsmallnews\Shop\Pages\Pay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Wsmallnews\Order\Enums\Order\PayStatus;
use Wsmallnews\Order\Enums\Order\Status;
use Wsmallnews\Order\Models\Order;
use Wsmallnews\Shop\Pages\Base;

class Cashier extends Base
{
    public ?string $order_sn;

    public ?Model $user;

    public ?Order $order;

    public function mount()
    {
        $this->user = Auth::guard()->user();

        $this->order_sn = request()->get('order_sn', '');

        $this->setOrder();
    }

    public function setOrder()
    {
        $this->order = Order::where('user_id', ($this->user ? $this->user->id : 0))->where('order_sn', $this->order_sn)->firstOrFail();
    }

    #[On('pay-start')]
    public function payStart($payMethod)
    {
        $this->setOrder();

        if (in_array($this->order->status, [Status::Closed])) {
            $this->error('订单已失效');
        }

        if (in_array($this->order->pay_status, [PayStatus::Paid])) {
            $this->error('订单已支付');
        }

        $payManager = app('sn-pay');
        $pay = $payManager->setPayable($this->order);
        // $pay = $payManager->setPayConfig($payMethod)

        $payRecord = $pay->driver($payMethod)->pay();

        if (in_array($payMethod, ['wechat', 'alipay'])) {
            $result = $pay->driver($payMethod)->thirdPrepay($payRecord);
        }
    }

    public function render()
    {
        return view('sn-shop::livewire.pay.cashier', [])->title('收银台');
    }
}
