@php
    use Wsmallnews\Shop\Support\Utils;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();

    $confirmUrl = Utils::route('order.confirm');
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content">
        {{-- 产品详情：产品数据与规格选择由 product 组件承载（scope/认证用户由调用方传入） --}}
        <livewire:sn-product::components.product.product :scope-type="$scopeType" :scope-id="$scopeId" :id="$id" :auth-user="$authUser" />
    </div>
</x-dynamic-component>
