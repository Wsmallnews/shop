<?php

namespace Wsmallnews\Shop\Order\Shortcuts;

use Wsmallnews\Order\Pipes\CommonPipes;
use Wsmallnews\Order\Shortcuts\Base;
use Wsmallnews\Product\Pipes\Calc\Extra as CalcExtraPipe;
use Wsmallnews\Product\Pipes\Calc\Product as CalcProductPipe;
use Wsmallnews\Product\Pipes\Check\Extra as CheckExtraPipe;
use Wsmallnews\Product\Pipes\Check\Product as CheckProductPipe;
use Wsmallnews\Product\Pipes\Fetch\Product as FetchProductPipe;
use Wsmallnews\Product\Pipes\Summary\Extra as SummaryExtraPipe;
use Wsmallnews\Product\Pipes\Summary\Product as SummaryProductPipe;

/**
 * 商城商品单配方（orders.type = 'product'）。
 *
 * 显式组合：每个阶段的完整清单 = 通用段（CommonPipes）+ 商品域管道；
 * 商城特有管道（购物车清理、优惠券等）在此追加，或经 OrderPipes 注册表
 * 由扩展包插入。退款/失效补偿当前为空清单，库存/优惠券扩展包落地后插回。
 */
class ProductShortcut extends Base
{
    public function getFetchPipes(): array
    {
        return array_merge(CommonPipes::fetch(), [
            'product' => FetchProductPipe::class,
        ]);
    }

    public function getCheckPipes(): array
    {
        return array_merge(CommonPipes::check(), [
            'product' => CheckProductPipe::class,
            'extra' => CheckExtraPipe::class,
        ]);
    }

    public function getCalcPipes(): array
    {
        return array_merge(CommonPipes::calc(), [
            'product' => CalcProductPipe::class,
            'extra' => CalcExtraPipe::class,
        ]);
    }

    public function getSummaryPipes(): array
    {
        return array_merge(CommonPipes::summary(), [
            'product' => SummaryProductPipe::class,
            'extra' => SummaryExtraPipe::class,
        ]);
    }

    public function getCreatingPipes(): array
    {
        return CommonPipes::creating();       // 无商城特有创建段
    }

    public function getRefundPipes(): array
    {
        return [];
    }

    public function getInvalidPipes(): array
    {
        return [];
    }
}
