<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler;

use Illuminate\Support\Facades\Facade;

class IndentedViewFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PersistentIndentingViewServiceProvider::class;
    }
}