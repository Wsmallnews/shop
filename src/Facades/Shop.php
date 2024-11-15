<?php

namespace Wsmallnews\Shop\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wsmallnews\Shop\Shop
 */
class Shop extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wsmallnews\Shop\Shop::class;
    }
}
