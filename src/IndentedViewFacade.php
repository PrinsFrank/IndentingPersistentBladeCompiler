<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler;

use Illuminate\Support\Facades\Facade;

class IndentedViewFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return IndentedViewServiceProvider::class;
    }
}