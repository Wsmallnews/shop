<?php

namespace Wsmallnews\Shop\Pages;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Index extends Base
{

    public function mount()
    {

        // \Wsmallnews\Support\Facades\Support::registerEasySms([
        //     'default' => [
        //         'gateways' => [
        //             'aliyun' => [
        //                 'access_key_id' => 'LTAI5t95555555555555',
        //             ],
        //         ],
        //     ],
        // ]);
        // exit;
    }



    public function render()
    {
        return view('sn-shop::livewire.index', [])->title('商城首页');
    }
}
