@php
    $detailRouteName = 'sn-shop.product.detail';
@endphp

<div>
    <x-sn-support::base.block class="container mx-auto rounded-lg overflow-hidden p-4">
        <livewire:sn-product-list scope_type="shop" :detail-route-name="$detailRouteName" />
    </x-sn-support::base.block>
</div>