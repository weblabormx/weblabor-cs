<?php

namespace Weblabor\CodeStandars\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Weblabor\CodeStandars\CodeStandars
 */
class CodeStandars extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Weblabor\CodeStandars\CodeStandars::class;
    }
}
