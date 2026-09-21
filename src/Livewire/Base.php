<?php

namespace Wsmallnews\Shop\Livewire;

use Wsmallnews\Shop\Support\Utils;
use Wsmallnews\Support\Livewire\Base as BaseComponent;

class Base extends BaseComponent
{
    public function getScopeType(): ?string
    {
        return Utils::getScopeType() ?? null;
    }

    public function getScopeId(): ?int
    {
        return Utils::getScopeId() ?? null;
    }

    public function getScopeable(): array
    {
        return Utils::getScopeable();
    }

    public function getPageContainer(): string
    {
        return Utils::getPageContainer();
    }
}
