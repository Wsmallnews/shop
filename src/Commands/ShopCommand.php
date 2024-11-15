<?php

namespace Wsmallnews\Shop\Commands;

use Illuminate\Console\Command;

class ShopCommand extends Command
{
    public $signature = 'shop';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
