@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Shop\Support\Utils;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();

    $userAddressColumns = [
        'md' => 2,
        '2xl' => 3,
    ];
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content" x-data="snShopCashier({})">
        @if ($order)
            {{-- 订单金额与支付方式（pay 包组件：选中后向上分发 pay-start，由本页 payStart 处理） --}}
            <div class="sn-container sn-padded w-full">
                <div class="flex items-end justify-between w-full">
                    <div class="sn-descript-text">{{ __('sn-shop::shop.frontend.pay_cashier') }}</div>
                    <div class="flex items-end sn-gap">
                        <div>{{ __('sn-order::order.confirm.pay_fee') }}：</div>
                        <div class="text-xl font-bold sn-primary-text">{{ sn_money()->format($order->remain_pay_fee) }}</div>
                    </div>
                </div>
            </div>

            <div class="sn-container sn-padded w-full">
                <livewire:sn-pay::components.pay-methods :user="$user" :columns="$userAddressColumns" />
            </div>
        @else
            {{-- 无订单上下文（未登录 / 单号缺失 / 订单不存在） --}}
            <x-sn-support::empty
                :icon="Heroicon::OutlinedCreditCard"
                icon-color="gray"
                icon-size="md"
                heading="{{ __('sn-shop::shop.frontend.pay_cashier') }}"
                description="{{ __('sn-shop::shop.frontend.pay_no_order') }}"
            />
        @endif
    </div>
</x-dynamic-component>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('snShopCashier', () => ({
                init() {
                    this.$wire.on('pay-finish', (data) => {
                        // 支付完成：跳转结果页（order 包提供）
                    });
                },
            }));
        });
    </script>
@endonce
