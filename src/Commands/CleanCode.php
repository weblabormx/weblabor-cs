<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;

class CleanCode extends Command
{
    public $signature = 'clean:code';

    public $description = 'Execute the code standars';

    public function handle(): int
    {
        $this->commit("Changes not saved");
        $this->executeCsFixer();

        // Execute commands
        $this->executeCommand('optimize:front');
        $this->executeCommand('optimize:remove-comments');
        $this->executeCommand('optimize:traits-one-line');
        $this->executeCommand('optimize:variables-one-line');

        $this->executeCsFixer();
        return self::SUCCESS;
    }

    private function executeCsFixer()
    {
        // Execute laravel Pint
        exec("./vendor/bin/pint --config vendor/weblabormx/weblabor-cs/pint.json");
        $this->commit("Laravel Pint");

        // Execute CS Fixer
        exec("php php-cs-fixer.phar fix . --config vendor/weblabormx/weblabor-cs/.php-cs-fixer.php");
        $this->commit("PHP CS Fixer");
    }

    private function executeCommand(string $command): void
    {
        $this->call($command);
        $this->commit($command);
    }

    private function commit($name)
    {
        exec('git add .');
        exec('git commit -m "'.$name.'"');
    }
}
