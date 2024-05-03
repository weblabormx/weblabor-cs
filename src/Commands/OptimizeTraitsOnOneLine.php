<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeTraitsOnOneLine extends Command
{
    protected $signature = 'optimize:traits-one-line';

    protected $description = 'Put traits on one lines';

    public function handle()
    {
        $folders = config('weblabor-cs.code_directories');
        foreach($folders as $folder) {
            $files = File::allFiles(base_path($folder));
            foreach ($files as $file) {
                $content = File::get($file->getPathname());
                $pattern = '/[\bclass\|\btrait\]\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff ]*\s*{(([ \t\n]*use[a-zA-Z, ;]*)+)/s';
                if (! preg_match($pattern, $content)) {
                    continue;
                }
                preg_match_all($pattern, $content, $matches);
                $variables = collect(explode("\n", $matches[1][0]))->map(function ($item) {
                    return trim($item);
                })->filter(function($field) {
                    return isset($field) && strlen($field) > 0;
                })->values();
                if ($variables->count() < 2) {
                    continue;
                }

                foreach ($variables as $variable) {
                    $content = preg_replace('/'.$variable.'\n[ \t]*/s', '', $content);
                }

                $variables = $variables->map(function ($item) {
                    $item = str_replace('use', '', $item);
                    $item = str_replace(';', '', $item);

                    return explode(',', trim($item));
                })->flatten()->map(function ($item) {
                    return trim($item);
                })->implode(', ');
                $variables = "\n\tuse $variables;";

                $content = preg_replace('/[\bclass\|\btrait\]\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff ]*\s*{/s', '$0'.$variables, $content, 1);
                File::put($file->getPathname(), $content);
            }
        }

        $this->info('Traits on One line executed successfully.');
    }
}
