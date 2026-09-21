@php
    use Wsmallnews\Shop\ShopPlugin;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content">
        <div class="w-full mx-auto @2xl:w-96 sn-padded">
            <livewire:sn-user::components.auth.verify-email :module="app(ShopPlugin::class)->getId()" :type="$type" />
        </div>
    </div>
</x-dynamic-component>
