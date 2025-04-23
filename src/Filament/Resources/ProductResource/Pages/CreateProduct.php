<?php

namespace Wsmallnews\Shop\Filament\Resources\ProductResource\Pages;

use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Wsmallnews\Product\Enums;
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

    protected function afterCreate(): void
    {
        // 将规格中设置的 原价和现价同步到 product 表，多规格同步最低价格的记录信息
        $rawState = $this->form->getRawState();
        if ($rawState['sku_type'] === Enums\ProductSkuType::Single->value) {
            $variant = $rawState['variant'] ?? [];
            $this->getRecord()->original_price = $variant['original_price'] ?? 0;
            $this->getRecord()->price = $variant['price'] ?? 0;
            $this->getRecord()->save();
        } elseif ($rawState['sku_type'] === Enums\ProductSkuType::Multiple->value) {
            $recursions = $rawState['sku_multiple']['recursions'] ?? collect([]);
            $recursions = $recursions instanceof Collection ? $recursions : collect($recursions);
            $minVariant = $recursions->sortBy('price')->first();

            $this->getRecord()->original_price = $minVariant['original_price'] ?? 0;
            $this->getRecord()->price = $minVariant['price'] ?? 0;
            $this->getRecord()->save();
        } elseif ($rawState['sku_type'] === Enums\ProductSkuType::Unit->value) {
            throw new \Exception('多单位商品暂未支持');
        }
    }
}
