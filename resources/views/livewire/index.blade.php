@php
    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content">
        {{-- 商城首页：产品列表（产品数据与分页由 product 组件承载，scope 与详情路由由调用方传入） --}}
        <livewire:sn-product::components.product.products
            :scope-type="$scopeType"
            :scope-id="$scopeId"
            href-route="sn-shop.product.detail"
        />
    </div>
</x-dynamic-component>
