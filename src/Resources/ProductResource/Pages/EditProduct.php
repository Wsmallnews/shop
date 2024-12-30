<?php

namespace Wsmallnews\Shop\Resources\ProductResource\Pages;

use Filament\Actions;
use Wsmallnews\Product\Resources\ProductResource\Pages\EditProduct as EditProductBase;
use Wsmallnews\Shop\Resources\ProductResource;

class EditProduct extends EditProductBase
{
    protected static string $resource = ProductResource::class;


    public function boot(): void
    {
        // db_listen();
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
