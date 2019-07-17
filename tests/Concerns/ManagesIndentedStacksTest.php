<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Concerns;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewFinderInterface;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewFactory;

class ManagesIndentedStacksTest extends TestCase
{
    public function testNoStackPush(): void
    {
        $factory = $this->getFactory();
        $this->assertEquals('', $factory->yieldPushContent('foo'));
        $this->assertEquals('', $factory->yieldPushContent('foo', '    '));
        $this->assertEquals('', $factory->yieldPushContent('foo', '', '    '));
    }

    public function testPrepends(): void
    {
        $factory = $this->getFactory();
        $factory->startPush('foo');
        echo ', Hello!';
        $factory->stopPush();
        $factory->startPrepend('foo');
        echo 'hi';
        $factory->stopPrepend();
        $this->assertEquals('hi, Hello!'.PHP_EOL, $factory->yieldPushContent('foo'));
        $this->assertEquals('hi, Hello!'.PHP_EOL, $factory->yieldPushContent('foo', '    '));
        $this->assertEquals('hi, Hello!'.PHP_EOL, $factory->yieldPushContent('foo', '', '    '));
    }

    public function testSingleStackPush(): void
    {
        $factory = $this->getFactory();
        $factory->startPush('foo');
        echo 'hi';
        $factory->stopPush();
        $this->assertEquals('hi'.PHP_EOL, $factory->yieldPushContent('foo'));
        $this->assertEquals('hi'.PHP_EOL, $factory->yieldPushContent('foo', '    '));
        $this->assertEquals('hi'.PHP_EOL, $factory->yieldPushContent('foo', '', '    '));
    }

    public function testSingleMultiLineStackPush(): void
    {
        $factory = $this->getFactory();
        $factory->startPush('foo');
        echo 'hi'.PHP_EOL;
        $factory->stopPush();
        $this->assertEquals('hi'.PHP_EOL, $factory->yieldPushContent('foo'));
        $this->assertEquals('hi'.PHP_EOL, $factory->yieldPushContent('foo', '    '));
        $this->assertEquals('hi'.PHP_EOL, $factory->yieldPushContent('foo', '', '    '));
    }

    public function testMultipleStackPush(): void
    {
        $factory = $this->getFactory();
        $factory->startPush('foo');
        echo 'hi';
        $factory->stopPush();
        $factory->startPush('foo');
        echo ', Hello!';
        $factory->stopPush();
        $this->assertEquals('hi, Hello!'.PHP_EOL, $factory->yieldPushContent('foo'));
        $this->assertEquals('hi, Hello!'.PHP_EOL, $factory->yieldPushContent('foo', '    '));
        $this->assertEquals('hi, Hello!'.PHP_EOL, $factory->yieldPushContent('foo', '', '    '));
    }

    public function testMultipleMultiLineStackPush(): void
    {
        $factory = $this->getFactory();
        $factory->startPush('foo');
        echo 'hi'.PHP_EOL;
        $factory->stopPush();
        $factory->startPush('foo');
        echo ', Hello!'.PHP_EOL;
        $factory->stopPush();
        $this->assertEquals('hi'.PHP_EOL.', Hello!'.PHP_EOL, $factory->yieldPushContent('foo'));
        $this->assertEquals('hi'.PHP_EOL.'    , Hello!'.PHP_EOL, $factory->yieldPushContent('foo', '    '));
        $this->assertEquals('hi'.PHP_EOL.'    , Hello!'.PHP_EOL, $factory->yieldPushContent('foo', '', '    '));
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