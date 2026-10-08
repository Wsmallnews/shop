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

            <div class="sn-split-main">
                {{-- 地址簿管理（内容贴卡型：卡片皮由组件自身按 contained 提供） --}}
                <livewire:sn-profile::components.address.addresses :owner="$user" />
            </div>
        </div>
    </div>
</x-dynamic-component>
