<?php

namespace Weblabor\CodeStandars;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Weblabor\CodeStandars\Commands;

class CodeStandarsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('weblabor-cs')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_weblabor-cs_table')
            ->hasCommand(Commands\CleanCode::class)
            ->hasCommand(Commands\OptimizeFront::class)
            ->hasCommand(Commands\OptimizeRemovePhpDocs::class)
            ->hasCommand(Commands\OptimizeTraitsOnOneLine::class)
            ->hasCommand(Commands\OptimizeVariablesOnOneLine::class);
    }
}
