@php
    use Wsmallnews\Shop\Support\Utils;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();

    $breadcrumbs = [
        ['label' => __('sn-shop::shop.frontend.home'), 'url' => Utils::route('index')],
        ['label' => __('sn-shop::shop.frontend.products'), 'url' => Utils::route('products')],
    ];
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content">
        <div class="sn-descript-text w-full flex items-center gap-2 text-left">
            {{ __('sn-shop::shop.frontend.current_location') }} :
            <x-sn-support::breadcrumbs :breadcrumbs="$breadcrumbs" />
        </div>

        {{-- 产品列表页：产品数据与分页由 product 组件承载，scope 与详情路由由调用方传入 --}}
        <livewire:sn-product::components.product.products
            :scope-type="$scopeType"
            :scope-id="$scopeId"
            href-route="sn-shop.product.detail"
            page-type="paginator"
        />
    </div>
</x-dynamic-component>
