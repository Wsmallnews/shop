@php
    use Wsmallnews\Shop\Support\Utils;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();

    $confirmUrl = Utils::route('order.confirm');
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content" x-data="snShopDetail({})">
        {{-- 产品详情（产品数据由 product 组件承载，scope 由调用方传入） --}}
        <livewire:sn-product-detail :scope-type="$scopeType" :scope-id="$scopeId" :id="$id" />
    </div>
</x-dynamic-component>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('snShopDetail', () => ({
                init() {
                    // 产品组件发起购买：携带有货规格跳转确认订单页
                    this.$wire.on('product-buy', (data) => {
                        const params = new URLSearchParams(data);
                        window.location.href = `{{ $confirmUrl }}?${params.toString()}`;
                    });
                },
            }));
        });
    </script>
@endonce
