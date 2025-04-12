<?php

namespace Wsmallnews\Shop\Filament\Resources\ProductResource\Pages;

use Livewire\Attributes\Locked;
use Wsmallnews\Product\Resources\ProductResource\Pages\ViewProduct as ViewProductsBase;
use Wsmallnews\Shop\Filament\Resources\ProductResource;

class ViewProduct extends ViewProductsBase
{
    protected static string $resource = ProductResource::class;

    #[Locked]
    public string $scope_type = 'shop';
}
