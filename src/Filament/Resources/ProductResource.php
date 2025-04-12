<?php

namespace Wsmallnews\Shop\Filament\Resources;

use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Wsmallnews\Product\Models\Product as ProductModel;
use Wsmallnews\Product\Repositories\Fields as FieldsRepository;
use Wsmallnews\Product\Repositories\Columns as ColumnsRepository;
use Wsmallnews\Product\ResourceBuilder\ProductResourceBuilder;
use Wsmallnews\Shop\Filament\Resources\ProductResource\Pages;
use Wsmallnews\Support\Filament\Resources\SupportResource;

class ProductResource extends SupportResource
{
    protected static ?string $model = ProductModel::class;

    protected static ?string $navigationGroup = '产品管理';
    protected static ?string $navigationLabel = '产品管理';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = '产品';

    protected static ?string $pluralModelLabel = '产品库';

    protected static ?string $slug = '/shop/products';

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;


    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditProduct::class,
            // Pages\ManageSkuPrices::class,
            // Pages\ManageProductMedia::class,
            // Pages\EditCustomerContact::class,
            // Pages\ManageCustomerAddresses::class,
            // Pages\ManageCustomerPayments::class,
        ]);
    }


    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Components\Section::make()
    //                 ->schema([
    //                     FieldsRepository::type()->columnSpan(2),
    //                     FieldsRepository::title()->columnSpan(2),
    //                     FieldsRepository::hiddenSkuType(),
    //                     FieldsRepository::image()->columnSpan(2),
    //                     FieldsRepository::hiddenStockType(),
    //                     Components\Group::make()
    //                         ->relationship('skuPrice')
    //                         ->schema([
    //                             FieldsRepository::hiddenSkuType(),
    //                             FieldsRepository::price()->columnSpan(2),
    //                             FieldsRepository::hiddenSkuPriceStatus()->columnSpan(2),
    //                         ])
    //                         ->columnSpanFull(),
    //                     FieldsRepository::status()->columnSpan(2),
    //                 ]),
    //         ]);
    // }



    // 字段排序
    // filter 排序
    // 列搜索，或者筛选
    // 是否清空选中列复选框
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColumnsRepository::id(),
                // ColumnsRepository::productInfo(),
                ColumnsRepository::image()->disk('public'),
                ColumnsRepository::title(),
                ColumnsRepository::price(),
                ColumnsRepository::viewNum(),
                ColumnsRepository::updatedAt(),
                ColumnsRepository::status(),
            ])
            ->filters(
                (new ProductResourceBuilder)->filters(),
                // layout: \Filament\Tables\Enums\FiltersLayout::AboveContentCollapsible       // 这个更好，可以在表格上面展示搜索条件，可以折叠
            )
            ->deferFilters()        // 延迟过滤
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->searchPlaceholder('搜索产品标题')
            ->striped();
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
