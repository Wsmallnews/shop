<?php

namespace Wsmallnews\Shop\Filament\Resources\ProductResource\Pages;

use Livewire\Attributes\Locked;
use Wsmallnews\Product\ResourceBuilder\Traits\WizardForm;
use Wsmallnews\Shop\Filament\Resources\ProductResource;
use Wsmallnews\Support\Filament\Resources\BasePages\BaseCreateRecord;

class CreateProduct extends BaseCreateRecord
{
    use WizardForm;

    protected static string $resource = ProductResource::class;

    #[Locked]
    public string $scope_type = 'shop';

    protected function hasSkippableSteps(): bool
    {
        return true;
    }
}
