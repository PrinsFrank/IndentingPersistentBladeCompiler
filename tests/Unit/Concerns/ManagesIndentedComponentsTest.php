<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Concerns;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\ViewFinderInterface;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewFactory;

class ManagesIndentedComponentsTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testComponentHandling(): void
    {
        $factory = $this->getFactory();
        $factory->getFinder()->shouldReceive('find')->andReturn(__DIR__ . '/../fixtures/component.php');
        $factory->getEngineResolver()->shouldReceive('resolve')->andReturn(new PhpEngine);
        $factory->getDispatcher()->shouldReceive('dispatch');
        $factory->startComponent('component', ['name' => 'Frank']);
        $factory->slot('title');
        $factory->slot('website', 'prinsfrank.nl');
        echo 'title<hr>';
        $factory->endSlot();
        echo 'component';
        $contents = $factory->renderComponent();
        $this->assertEquals('title<hr> component Frank prinsfrank.nl'.PHP_EOL, $contents);
    }

    protected function getFactory(): IndentedViewFactory
    {
        return new IndentedViewFactory(
            m::mock(EngineResolver::class),
            m::mock(ViewFinderInterface::class),
            m::mock(DispatcherContract::class)
        );
    }
}