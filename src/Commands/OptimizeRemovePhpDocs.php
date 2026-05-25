<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeRemovePhpDocs extends Command
{
    protected $signature = 'optimize:remove-comments';

    protected $description = 'Remove PHP Docs';

    public function handle()
    {
        $folders = config('weblabor-cs.code_directories');
        foreach($folders as $folder) {
            $files = File::allFiles(base_path($folder));
            foreach ($files as $file) {
                $content = File::get($file->getPathname());

                $comments = [];
                $lines = explode("\n", $content);
                $comment = '';
                foreach ($lines as $line) {
                    if (strpos(trim($line), '/*') === 0) {
                        $comment .= $line."\n";
                        if (strpos(trim($line), '*/') !== false) {
                            $comments[] = $comment;
                            $comment = '';
                        }
                    } elseif (strpos(trim($line), '*/') !== false) {
                        $comment .= $line."\n";
                        $comments[] = $comment;
                        $comment = '';
                    } elseif (! empty($comment)) {
                        $comment .= $line."\n";
                    }
                }
                $comments = collect($comments)->filter(function ($item) {
                    return str_contains($item, '@');
                });
                if (count($comments) == 0) {
                    continue;
                }
                foreach ($comments as $comment) {
                    $content = str_replace($comment, '', $content);
                }
                File::put($file->getPathname(), $content);
            }
        }

        $this->info('PHP Docs removed successfully.');
    }
}
