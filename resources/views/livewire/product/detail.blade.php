@php
    $confirmUrl = route('sn-shop.order.confirm');
@endphp

<div class="container mx-auto" x-data="pageDetail({})">

    <div class="w-full">
        这个是商品详情的头部
    </div>

    <div class="w-full rounded-lg">
        <livewire:sn-product-detail :id="$id" />
    </div>
</div>


@assets
<script>
    function pageDetail({
    }) {
        return {
            
            init () {
                this.$wire.on('product-buy', (data) => {
                    let params = new URLSearchParams(data);
                    window.location.href = "{{ $confirmUrl }}" + '?' + params.toString();
                });
            },
        }
    }
</script>
@endassets