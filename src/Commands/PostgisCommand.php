<?php

namespace Clickbar\Postgis\Commands;

use Illuminate\Console\Command;

class PostgisCommand extends Command
{
    public $signature = 'postgis';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
