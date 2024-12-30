<?php

namespace Wsmallnews\Shop\Resources;

use Wsmallnews\Product\Resources\ProductResource as ProductResourceBase;
use Wsmallnews\Shop\Resources\ProductResource\Pages;

class ProductResource extends ProductResourceBase
{
    protected static ?string $navigationGroup = '商城产品管理组';

    protected static ?string $navigationLabel = '商城产品管理';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = '商城产品';

    protected static ?string $pluralModelLabel = '商城产品库';

    protected static ?string $slug = '/shop/products';

    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
