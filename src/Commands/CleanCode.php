<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CleanCode extends Command
{
    public $signature = 'clean:code
                         {--no-commit : Don\'t execute git commit}';
    public $description = 'Execute the code standards';

    public function handle(): int
    {
        $this->commit("Changes not saved");
        $this->executeCsFixer();

        // Execute commands
        $this->callAndCommit('optimize:namespaces');
        $this->callAndCommit('optimize:traits-one-line');
        $this->callAndCommit('optimize:variables-one-line');

        $this->executeCsFixer();
        return self::SUCCESS;
    }

    private function executeCsFixer()
    {
        // Execute laravel Pint
        $this->exec("./vendor/bin/pint --config vendor/weblabormx/weblabor-cs/pint.json");
        $this->commit("Laravel Pint");

        // Execute CS Fixer
        $this->exec("php php-cs-fixer.phar fix . --config vendor/weblabormx/weblabor-cs/.php-cs-fixer.php");
        $this->commit("PHP CS Fixer");
    }

    private function callAndCommit(string $command): void
    {
        $this->call($command);
        $this->commit($command);
    }

    private function commit($name)
    {
        if (
            !config('weblabor-cs.automatic_commit') ||
            $this->option('no-commit')
        ) {
            return;
        }

        if ($this->exec('git add .')) {
            $this->exec('git commit -m "${:COMMIT_MSG}"', ['COMMIT_MSG' => $name]);
        }
    }


    private function exec(string $command, array $args = []): bool
    {
        $process = Process::fromShellCommandline($command, base_path(), [...$_ENV, ...$args])
            ->setTimeout(60 * 5);

        $process->run();

        if ($process->isSuccessful()) return true;

        $this->error($process->getErrorOutput());
        return false;
    }
}
