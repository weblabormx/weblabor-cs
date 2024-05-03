<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeVariablesOnOneLine extends Command
{
    protected $signature = 'optimize:variables-one-line';

    protected $description = 'Put variables on one lines';

    public function handle()
    {
        $folders = config('weblabor-cs.code_directories');
        foreach($folders as $folder) {
            $files = File::allFiles(base_path($folder));
            foreach ($files as $file) {
                $content = File::get($file->getPathname());
                $types = ['public', 'private', 'protected'];
                foreach ($types as $type) {
                    $pattern = '/'.$type.'\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[^;]*;/s';
                    preg_match_all($pattern, $content, $matches);
                    $variables = collect($matches[0])->filter(function ($item) {
                        return ! str_contains($item, '=');
                    });
                    if ($variables->count() < 2) {
                        continue;
                    }

                    $use_traits = preg_match('/[\bclass\|\btrait\]\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff ]*\s*{[ \t\n]*use[a-zA-Z, ;]*/s', $content);
                    foreach ($variables as $variable) {
                        $variable = str_replace('$', '\$', $variable);
                        $content = preg_replace('/'.$variable.'\n[ \t]*/s', '', $content);
                    }
                    $variables = $variables->map(function ($item) use ($type) {
                        return '$'.preg_replace('/'.$type.'\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[^;]*;/s', '$1', $item);
                    })->implode(', ');
                    $variables = "\n\t$type $variables;";

                    if ($use_traits) {
                        $content = preg_replace('/[\bclass\|\btrait\]\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff ]*\s*{[ \t\n]*use[a-zA-Z, ;]*\n[ \t]*/s', "$0$variables", $content, 1);
                    } else {
                        $content = preg_replace('/[\bclass\|\btrait\]\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff ]*\s*{/s', '$0'.$variables, $content, 1);
                    }
                }
                File::put($file->getPathname(), $content);
            }
        }

        $this->info('Variables on one line executed successfully.');
    }
}
