@php
    // $cashierUrl = route('sn-shop.pay.cashier');

    $payMethods = ['money', 'alipay'];

    $userAddressColumns = [
        'md' => 2,
        '2xl' => 3,
    ]
@endphp

<div class="container mx-auto" x-data="pageCashier({})">

    <div class="w-full">
        这个是收银台
    </div>

    应支付金额{{ $order->remain_pay_fee }}

    <div class="w-full rounded-lg overflow-hidden">
        <livewire:sn-pay-methods :user="$user" :pay-methods="$payMethods" :columns="$userAddressColumns" />
    </div>
</div>


@assets
<script>
    function pageCashier({
    }) {
        return {
            init () {
                this.$wire.on('pay-finish', (data) => {
                    // let params = new URLSearchParams(data);
                });
            },
        }
    }
</script>
@endassets