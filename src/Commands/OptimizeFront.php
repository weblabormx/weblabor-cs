<?php

namespace Weblabor\CodeStandars\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeFront extends Command
{
    protected $signature = 'optimize:front';

    protected $description = 'Optimize Front classes';

    public function handle()
    {
        $files = File::allFiles(app_path('Front/Resources'));

        foreach ($files as $file) {
            $content = File::get($file->getPathname());
            $content = $this->optimizeNamespace($content, 'WeblaborMx\Front\Inputs');
            $content = $this->optimizeNamespace($content, 'WeblaborMx\Front\Texts');
            $content = $this->optimizeNamespace($content, 'App\Front\Filters', true);
            $content = $this->optimizeNamespace($content, 'App\Front\Actions', true);
            $content = $this->optimizeNamespace($content, 'App\DocumentGroups', true);
            $content = $this->optimizeNamespace($content, 'App\Documents', true);
            $content = $this->optimizeNamespace($content, 'App\Jobs', true);
            File::put($file->getPathname(), $content);
        }

        $this->info('Front classes optimized successfully.');
    }

    private function optimizeNamespace($content, $namespace, $use_new = false)
    {
        // use 1 = ::make, 2 = new o ::
        $type = explode('\\', $namespace);
        $type = $type[count($type) - 1];
        $models = $this->getNamespaces($content, $namespace);
        $namespace2 = str_replace('\\', '\\\\', $namespace);
        if (count($models) == 0) {
            return $content;
        }
        foreach ($models as $model) {
            $original_model = $model;
            $model = str_replace('\\', '\\\\', $model);
            $content = preg_replace("/use $namespace2\\\\".$model.";.*\n/", '', $content);
            $content = preg_replace("/\b$model::/", "$type\\$model::", $content);
            if ($use_new) {
                $content = str_replace("new $model", "new $type\\$original_model", $content);
            }

            if (str_contains($model, '\\')) {
                $clean_model = explode('\\', $model);
                $clean_model = $clean_model[count($clean_model) - 1];
                $content = preg_replace("/\b$clean_model::/", "$type\\$model::", $content);
                if ($use_new) {
                    $content = str_replace("new $clean_model", "new $type\\$original_model", $content);
                }
            }

        }

        // Asegurarse de que 'use WeblaborMx\Front\Inputs;' está presente
        if (! str_contains($content, "use $namespace;")) {
            $content = str_replace('use App\Front\Resources\Resource;', "use $namespace;\nuse App\Front\Resources\Resource;", $content);
        }
        if (! str_contains($content, "use $namespace;")) {
            $content = preg_replace("/namespace .*;\n/", "$0\nuse $namespace;", $content);
        }

        return $content;
    }

    private function getNamespaces($content, $folder)
    {
        $folder = str_replace('\\', '\\\\', $folder);
        $namespaces = [];
        preg_match_all('/use\s+('.$folder.'\\\\(.*?));/i', $content, $matches);
        foreach ($matches[2] as $match) {
            $namespaces[] = $match;
        }

        return $namespaces;
    }
}
