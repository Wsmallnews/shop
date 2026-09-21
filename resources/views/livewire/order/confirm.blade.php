@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Shop\Support\Utils;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();

    $cashierUrl = Utils::route('pay.cashier');
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content" x-data="snShopOrderConfirm({})">
        {{-- @sn todo 嵌入 order 包确认组件（sn-order-confirm，:buyer/:relate-items/:order-type/:from）；
            其收货地址子组件（sn-user::components.choose-address）待 user 包迁移 Components/ 到 Livewire 命名空间后可用 --}}
        <x-sn-support::empty
            :icon="Heroicon::OutlinedShoppingBag"
            icon-color="gray"
            icon-size="md"
            heading="{{ __('sn-shop::shop.frontend.order_confirm') }}"
            description="{{ __('sn-shop::shop.frontend.order_pending') }}"
        />
    </div>
</x-dynamic-component>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('snShopOrderConfirm', () => ({
                init() {
                    // 订单创建完成：携单号跳转收银台
                    this.$wire.on('order-create-finish', (data) => {
                        const params = new URLSearchParams(data);
                        window.location.href = `{{ $cashierUrl }}?${params.toString()}`;
                    });
                },
            }));
        });
    </script>
@endonce
