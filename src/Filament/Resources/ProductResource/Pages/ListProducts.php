<?php

namespace Wsmallnews\Shop\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Wsmallnews\Product\Enums\ProductStatus;
use Wsmallnews\Shop\Filament\Resources\ProductResource;
use Wsmallnews\Support\Filament\Resources\BasePages\BaseListRecords;

class ListProducts extends BaseListRecords
{

    protected static string $resource = ProductResource::class;

    #[Locked]
    public string $scope_type = 'shop';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        $labels = ProductStatus::labels();

        $tabs = [
            'all' => Tab::make('all')->label('全部')
        ];
        foreach ($labels as $key => $label) {
            $tabs[$label['value']] = Tab::make($label['name'])->modifyQueryUsing(fn(Builder $query) => $query->{$label['value']}());
        }

        return $tabs;
    }
}
