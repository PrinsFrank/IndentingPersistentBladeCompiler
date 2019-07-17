<?php

namespace Illuminate\Tests\View;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewFinderInterface;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewFactory;
use stdClass;

class IndentedViewFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testRenderEachCreatesViewForEachItemInArrayWhenNoEmpty(): void
    {
        $factory = m::mock(IndentedViewFactory::class.'[make]', $this->getFactoryArgs());
        $factory->shouldReceive('make')
                ->once()
                ->with('foo', ['key' => 'bar', 'value' => 'baz', 'indenting' => '    '])
                ->andReturn($mockView1 = m::mock(stdClass::class));
        $factory->shouldReceive('make')
                ->once()
                ->with('foo', ['key' => 'breeze', 'value' => 'boom', 'indenting' => '    '])
                ->andReturn($mockView2 = m::mock(stdClass::class));
        $mockView1->shouldReceive('render')
                  ->once()
                  ->andReturn('dayle');
        $mockView2->shouldReceive('render')
                  ->once()
                  ->andReturn('rees');

        /** @var IndentedViewFactory $factory */
        $result = $factory->renderEach('foo', ['bar' => 'baz', 'breeze' => 'boom'], 'value', '    ');

        $this->assertEquals('dayle    rees', $result);
    }

    public function testEmptyViewsCanBeReturnedFromRenderEach(): void
    {
        $factory = m::mock(IndentedViewFactory::class.'[make]', $this->getFactoryArgs());
        $factory->shouldReceive('make')
                ->once()
                ->with('foo', ['indenting' => '    '])
                ->andReturn($mockView = m::mock(stdClass::class));
        $mockView->shouldReceive('render')
                 ->once()
                 ->andReturn('empty', ['indenting' => '    ']);

        /** @var IndentedViewFactory $factory */
        $this->assertEquals('empty', $factory->renderEach('view', [], 'iterator', 'foo', '    '));
    }

    public function testRawStringsMayBeReturnedFromRenderEach(): void
    {
        $this->assertEquals('foo', $this->getFactory()->renderEach('foo', [], 'item', 'raw|foo', '    '));
    }

    protected function getFactory(): IndentedViewFactory
    {
        return new IndentedViewFactory(
            m::mock(EngineResolver::class),
            m::mock(ViewFinderInterface::class),
            m::mock(DispatcherContract::class)
        );
    }

    protected function getFactoryArgs(): array
    {
        return [
            m::mock(EngineResolver::class),
            m::mock(ViewFinderInterface::class),
            m::mock(DispatcherContract::class),
        ];
    }
}
