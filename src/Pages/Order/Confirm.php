<?php

namespace Wsmallnews\Shop\Pages\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Wsmallnews\Shop\Pages\Base;

class Confirm extends Base
{
    public string $type = 'product';

    public array $relateItems = [];

    public string $from = 'product-detail';

    public ?Model $user;

    public function mount()
    {
        $this->user = Auth::guard()->user();

        $this->type = request()->get('type', $this->type);

        $relateItems = request()->get('relate_items', $this->relateItems);
        $this->relateItems = is_array($relateItems) ? $relateItems : json_decode($relateItems, true);

        $this->from = request()->get('from', $this->from);
    }

    public function render()
    {
        return view('sn-shop::livewire.order.confirm', [])->title('确认订单');
    }
}
