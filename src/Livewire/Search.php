<?php

namespace Wsmallnews\Shop\Livewire;

use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Facades\Seo;

/**
 * 全局搜索结果页（sn-shop.search.display = 'page' 时由搜索框跳转进入）。
 * 页面布局与路由由 shop 定义，结果区由 support 的核心组件渲染。
 */
class Search extends Base
{
    public function render()
    {
        Seo::title(__('sn-shop::shop.frontend.search_results'))->robots('noindex');

        return view('sn-shop::livewire.search', [
            //
        ])->layout(Utils::getLayout());
    }
}
