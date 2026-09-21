@php
    use Wsmallnews\Shop\ShopPlugin;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content">
        <h1 class="sn-content-text text-xl font-semibold">{{ __('sn-shop::shop.frontend.search_results') }}</h1>

        <livewire:sn-support::components.search-results :module="app(ShopPlugin::class)->getId()" :limit="10" placeholder="{{ __('sn-shop::shop.frontend.search_placeholder') }}" />
    </div>
</x-dynamic-component>
