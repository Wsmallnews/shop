@php
    
    $cashierUrl = route('sn-shop.pay.cashier');
@endphp

<div class="container mx-auto" x-data="pageConfirm({})">

    <div class="w-full">
        这个是确认订单的头部
    </div>

    <div class="w-full rounded-lg overflow-hidden">
        <livewire:sn-order-confirm :user="$user" :relate-items="$relateItems" :order_type="$type" :from="$from" />
    </div>
</div>


@assets
<script>
    function pageConfirm({
    }) {
        return {
            init () {
                this.$wire.on('order-create-finish', (data) => {
                    let params = new URLSearchParams(data);
                    window.location.href = "{{ $cashierUrl }}" + '?' + params.toString();
                });
            },
        }
    }
</script>
@endassets