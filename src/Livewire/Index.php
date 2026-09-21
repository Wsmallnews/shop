<?php

namespace Wsmallnews\Shop\Livewire;

use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

class Index extends Base
{
    public function render()
    {
        // 商城首页不声明页面标题（渲染时仅输出站点名），声明 WebSite 结构化数据
        Seo::website();

        return view('sn-shop::livewire.index', [
        ])->layout(Utils::getLayout());
    }
}
