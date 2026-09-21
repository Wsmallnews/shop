<?php

namespace Wsmallnews\Shop\Livewire\Order;

use Illuminate\Database\Eloquent\Model;
use Wsmallnews\Shop\Livewire\Base;
use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Confirm extends Base
{
    public string $type = 'product';

    public array $relateItems = [];

    public string $from = 'product-detail';

    public ?Model $user;

    public function mount(): void
    {
        $this->user = Utils::getAuthUser();

        $this->type = request()->get('type', $this->type);

        $relateItems = request()->get('relate_items', $this->relateItems);
        $this->relateItems = is_array($relateItems) ? $relateItems : json_decode($relateItems, true);

        $this->from = request()->get('from', $this->from);
    }

    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.order_confirm'))->robots('noindex');

        return view('sn-shop::livewire.order.confirm', [
        ])->layout(Utils::getLayout());
    }
}
