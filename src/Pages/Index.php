<?php

namespace Wsmallnews\Shop\Pages;

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
