<?php

namespace Wsmallnews\Shop\Resources\ProductResource\Pages;

use Filament\Actions;
use Wsmallnews\Product\Resources\ProductResource\Pages\ListProducts as ListProductsBase;
use Wsmallnews\Shop\Resources\ProductResource;

class ListProducts extends ListProductsBase
{
    protected static string $resource = ProductResource::class;



}
