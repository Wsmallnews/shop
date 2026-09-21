@php
    use Wsmallnews\Shop\ShopPlugin;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content">
        <div class="w-full mx-auto @2xl:w-96 sn-padded">
            <livewire:sn-user::components.auth.reset-password :module="app(ShopPlugin::class)->getId()" />
        </div>
    </div>
</x-dynamic-component>
