<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Concerns;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Illuminate\View\ViewFinderInterface;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedView;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewFactory;

class ManagesIndentedLayoutsTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testYieldDefault(): void
    {
        $factory = $this->getFactory();
        $this->assertEquals('hi', $factory->yieldContent('foo', 'hi', '    '));
    }

    public function testYieldDefaultIsEscaped(): void
    {
        $factory = $this->getFactory();
        $this->assertEquals('&lt;p&gt;hi&lt;/p&gt;', $factory->yieldContent('foo', '<p>hi</p>', '    '));
    }

    public function testYieldDefaultViewIsNotEscapedTwice(): void
    {
        $factory = $this->getFactory();
        $view = m::mock(IndentedView::class);
        $view->shouldReceive('__toString')
             ->once()
             ->andReturn('<p>hi</p>&lt;p&gt;already escaped&lt;/p&gt;');
        $this->assertEquals('<p>hi</p>&lt;p&gt;already escaped&lt;/p&gt;', $factory->yieldContent('foo', $view, '    '));
    }

    public function testBasicSectionHandling(): void
    {
        $factory = $this->getFactory();
        $factory->startSection('foo');
        echo 'hi';
        $factory->stopSection();
        $this->assertEquals('hi', $factory->yieldContent('foo', '    '));
        $this->assertEquals('hi', $factory->yieldContent('foo', '', '    '));

        $factory->startSection('bar');
        echo 'foo'.PHP_EOL.'bar';
        $factory->stopSection();
        $this->assertEquals('foo'.PHP_EOL.'    bar', $factory->yieldContent('bar', '    '));
        $this->assertEquals('foo'.PHP_EOL.'    bar', $factory->yieldContent('bar', '', '    '));
    }

    public function testBasicSectionDefault(): void
    {
        $factory = $this->getFactory();
        $factory->startSection('foo', 'hi');
        $this->assertEquals('hi', $factory->yieldContent('foo', '    '));
        $this->assertEquals('hi', $factory->yieldContent('foo', '', '    '));
    }

    public function testBasicSectionDefaultIsEscaped(): void
    {
        $factory = $this->getFactory();
        $factory->startSection('foo', '<p>hi</p>');
        $this->assertEquals('&lt;p&gt;hi&lt;/p&gt;', $factory->yieldContent('foo', '    '));
        $this->assertEquals('&lt;p&gt;hi&lt;/p&gt;', $factory->yieldContent('foo', '', '    '));
    }

    public function testBasicSectionDefaultViewIsNotEscapedTwice(): void
    {
        $factory = $this->getFactory();
        $view = m::mock(View::class);
        $view->shouldReceive('__toString')
             ->andReturn('<p>hi</p>&lt;p&gt;already escaped&lt;/p&gt;');
        $factory->startSection('foo', $view);
        $this->assertEquals('<p>hi</p>&lt;p&gt;already escaped&lt;/p&gt;', $factory->yieldContent('foo', '    '));
        $this->assertEquals('<p>hi</p>&lt;p&gt;already escaped&lt;/p&gt;', $factory->yieldContent('foo', '', '    '));
    }

    public function testSectionExtending(): void
    {
        $placeholder = Factory::parentPlaceholder('foo');
        $factory = $this->getFactory();
        $factory->startSection('foo');
        echo 'hi '.$placeholder;
        $factory->stopSection();
        $factory->startSection('foo');
        echo 'there';
        $factory->stopSection();
        $this->assertEquals('hi there', $factory->yieldContent('foo', '    '));
        $this->assertEquals('hi there', $factory->yieldContent('foo', '', '    '));
    }

    public function testSectionMultipleExtending(): void
    {
        $placeholder = Factory::parentPlaceholder('foo');
        $factory = $this->getFactory();
        $factory->startSection('foo');
        echo 'hello '.$placeholder.' nice to see you '.$placeholder;
        $factory->stopSection();
        $factory->startSection('foo');
        echo 'my '.$placeholder;
        $factory->stopSection();
        $factory->startSection('foo');
        echo 'friend';
        $factory->stopSection();
        $this->assertEquals('hello my friend nice to see you my friend', $factory->yieldContent('foo', '    '));
        $this->assertEquals('hello my friend nice to see you my friend', $factory->yieldContent('foo', '', '    '));
    }

    public function testYieldSectionStopsAndYields(): void
    {
        $factory = $this->getFactory();
        $factory->startSection('foo');
        echo 'hi';
        $this->assertEquals('hi', $factory->yieldSection());
    }

    public function testYieldEmptySectionStopsAndYields(): void
    {
        $factory = $this->getFactory();
        $this->assertEquals('', $factory->yieldSection());
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