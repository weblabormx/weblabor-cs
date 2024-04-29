<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;

class CodeStandarsCommand extends Command
{
    public $signature = 'weblabor-cs';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
