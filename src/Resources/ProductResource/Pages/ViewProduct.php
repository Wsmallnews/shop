<?php

namespace Wsmallnews\Shop\Resources\ProductResource\Pages;

use Wsmallnews\Product\Resources\ProductResource\Pages\ViewProduct as ViewProductsBase;
use Wsmallnews\Shop\Resources\ProductResource;

class ViewProduct extends ViewProductsBase
{
    protected static string $resource = ProductResource::class;
}
