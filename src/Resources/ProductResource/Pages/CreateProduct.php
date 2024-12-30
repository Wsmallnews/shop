<?php

namespace Wsmallnews\Shop\Resources\ProductResource\Pages;

use Wsmallnews\Product\Resources\ProductResource\Pages\CreateProduct as CreateProductBase;
use Wsmallnews\Shop\Resources\ProductResource;

class CreateProduct extends CreateProductBase
{
    protected static string $resource = ProductResource::class;

    // protected function beforeFill(): void
    // {
    // }

    // protected function afterFill(): void
    // {
    // }
}
