<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;

class CleanCode extends Command
{
    public $signature = 'clean:code';
    public $description = 'Execute the code standars';

    public function handle(): int
    {
        $this->call('optimize:front');

        return self::SUCCESS;
    }
}
