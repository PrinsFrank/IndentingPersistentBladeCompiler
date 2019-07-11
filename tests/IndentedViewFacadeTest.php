<?php

namespace Illuminate\Tests\View;


use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewFacade;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewServiceProvider;

class IndentedViewFacadeTest extends TestCase
{
    public function testFacadeReturnsServiceProviderClassName(): void
    {
        $this->assertEquals(IndentedViewServiceProvider::class, IndentedViewFacade::getFacadeAccessor());
    }
}
