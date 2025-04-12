<?php

namespace Wsmallnews\Shop\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Livewire\Attributes\Locked;
use Wsmallnews\Product\ResourceBuilder\Traits\WizardForm;
use Wsmallnews\Shop\Filament\Resources\ProductResource;
use Wsmallnews\Support\Filament\Resources\BasePages\BaseEditRecord;

class EditProduct extends BaseEditRecord
{
    use WizardForm;

    protected static string $resource = ProductResource::class;

    #[Locked]
    public string $scope_type = 'shop';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }


    protected function hasSkippableSteps(): bool
    {
        return true;
    }
}
