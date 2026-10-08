@php
    use Wsmallnews\Shop\Support\Utils;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();

    $cashierUrl = Utils::route('pay.cashier');
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content flex flex-col sn-gap" x-data="snShopOrderConfirm({})">
        {{-- 收货地址选择：默认地址自动选中，选择后经 sn-profile-address:selected 事件
            通知 order 包 Confirm 组件（落单时写入订单地址快照） --}}
        <livewire:sn-profile::components.address.choose-address
            :owner="$user"
            :manage-url="Utils::route('profile.addresses')"
        />

        {{-- 订单确认与落单由 order 包组件承载（配方按 order_type 从注册表解析；
            scope 由商城传入：商品查询与订单落库均按商城分区） --}}
        <livewire:sn-order::components.confirm
            :buyer="$user"
            :order-type="$type"
            :relate-items="$relateItems"
            :from="$from"
            :scope-type="$scopeType"
            :scope-id="$scopeId"
        />
    </div>
</x-dynamic-component>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('snShopOrderConfirm', () => ({
                init() {
                    // 订单创建完成：携单号跳转收银台
                    this.$wire.on('sn-order-created', (data) => {
                        const params = new URLSearchParams(data);
                        window.location.href = `{{ $cashierUrl }}?${params.toString()}`;
                    });
                },
            }));
        });
    </script>
@endonce
