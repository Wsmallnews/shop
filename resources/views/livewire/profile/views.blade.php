@php
    use Wsmallnews\Shop\ShopPlugin;
    use Wsmallnews\Shop\Support\Utils;

    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();

    $user = Utils::getUser();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="sn-content">
        @if ($breadcrumbs)
            <div class="sn-descript-text w-full flex items-center gap-2 text-left">
                {{ __('sn-shop::shop.frontend.current_location') }} :
                <x-sn-support::breadcrumbs :breadcrumbs="$breadcrumbs" />
            </div>
        @endif

        {{-- 侧栏与内容区按比例分栏（宽容器 1:3/1:4）；窄容器上下堆叠（侧栏在上） --}}
        <div class="sn-split">
            <div class="w-full min-w-0">
                <livewire:sn-user::components.user.sidebar-menu :module="app(ShopPlugin::class)->getId()" />
            </div>
            <div class="sn-container sn-split-main">
                {{-- 浏览记录（行式列表型：行自带边距语义，容器只穿卡片皮不加 padded） --}}
                <livewire:sn-preference::components.views
                    :scope-type="$scopeType"
                    :scope-id="$scopeId"
                    :user="$user"
                    :preferencer="$user"
                    :manageable="true"
                    :contained="false"
                />
            </div>
        </div>
    </div>
</x-dynamic-component>
