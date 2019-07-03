<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests;

use Closure;
use ArrayAccess;
use Mockery as m;
use Illuminate\View\View;
use BadMethodCallException;
use Illuminate\View\Factory;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\View\Engine;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

class ViewTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testDataCanBeSetOnView(): void
    {
        $view = $this->getView();
        $view->with('foo', 'bar');
        $view->with(['baz' => 'boom']);
        $this->assertEquals(['foo' => 'bar', 'baz' => 'boom'], $view->getData());

        $view = $this->getView();
        $view->withFoo('bar')->withBaz('boom');
        $this->assertEquals(['foo' => 'bar', 'baz' => 'boom'], $view->getData());
    }

    public function testRenderProperlyRendersView(): void
    {
        $view = $this->getView(['foo' => 'bar']);
        $view->getFactory()->shouldReceive('incrementRender')->once()->ordered();
        $view->getFactory()->shouldReceive('callComposer')->once()->ordered()->with($view);
        $view->getFactory()->shouldReceive('getShared')->once()->andReturn(['shared' => 'foo']);
        $view->getEngine()->shouldReceive('get')->once()->with('path', ['foo' => 'bar', 'shared' => 'foo'])->andReturn('contents');
        $view->getFactory()->shouldReceive('decrementRender')->once()->ordered();
        $view->getFactory()->shouldReceive('flushStateIfDoneRendering')->once();

        $callback = function (View $rendered, $contents) use ($view) {
            $this->assertEquals($view, $rendered);
            $this->assertEquals('contents', $contents);
        };

        $this->assertEquals('contents', $view->render($callback));
    }

    public function testRenderHandlingCallbackReturnValues(): void
    {
        $view = $this->getView();
        $view->getFactory()->shouldReceive('incrementRender');
        $view->getFactory()->shouldReceive('callComposer');
        $view->getFactory()->shouldReceive('getShared')->andReturn(['shared' => 'foo']);
        $view->getEngine()->shouldReceive('get')->andReturn('contents');
        $view->getFactory()->shouldReceive('decrementRender');
        $view->getFactory()->shouldReceive('flushStateIfDoneRendering');

        $this->assertEquals('new contents', $view->render(function () {
            return 'new contents';
        }));

        $this->assertEmpty($view->render(function () {
            return '';
        }));

        $this->assertEquals('contents'.PHP_EOL, $view->render(function () {
            //
        }));
    }

    public function testRenderSectionsReturnsEnvironmentSections(): void
    {
        $view = m::mock(View::class.'[render]', [
            m::mock(Factory::class),
            m::mock(Engine::class),
            'view',
            'path',
            [],
        ]);

        $view->shouldReceive('render')->with(m::type(Closure::class))->once()->andReturn($sections = ['foo' => 'bar']);

        $this->assertEquals($sections, $view->renderSections());
    }

    public function testSectionsAreNotFlushedWhenNotDoneRendering(): void
    {
        $view = $this->getView(['foo' => 'bar']);
        $view->getFactory()->shouldReceive('incrementRender')->twice();
        $view->getFactory()->shouldReceive('callComposer')->twice()->with($view);
        $view->getFactory()->shouldReceive('getShared')->twice()->andReturn(['shared' => 'foo']);
        $view->getEngine()->shouldReceive('get')->twice()->with('path', ['foo' => 'bar', 'shared' => 'foo'])->andReturn('contents');
        $view->getFactory()->shouldReceive('decrementRender')->twice();
        $view->getFactory()->shouldReceive('flushStateIfDoneRendering')->twice();

        $this->assertEquals('contents'.PHP_EOL, $view->render());
        $this->assertEquals('contents'.PHP_EOL, (string) $view);
    }

    public function testViewNestBindsASubView(): void
    {
        $view = $this->getView();
        $view->getFactory()->shouldReceive('make')->once()->with('foo', ['data']);
        $result = $view->nest('key', 'foo', ['data']);

        $this->assertInstanceOf(View::class, $result);
    }

    public function testViewAcceptsArrayableImplementations(): void
    {
        $arrayable = m::mock(Arrayable::class);
        $arrayable->shouldReceive('toArray')->once()->andReturn(['foo' => 'bar', 'baz' => ['qux', 'corge']]);

        $view = $this->getView($arrayable);

        $this->assertEquals('bar', $view->foo);
        $this->assertEquals(['qux', 'corge'], $view->baz);
    }

    public function testViewGettersSetters(): void
    {
        $view = $this->getView(['foo' => 'bar']);
        $this->assertEquals($view->name(), 'view');
        $this->assertEquals($view->getPath(), 'path');
        $data = $view->getData();
        $this->assertEquals($data['foo'], 'bar');
        $view->setPath('newPath');
        $this->assertEquals($view->getPath(), 'newPath');
    }

    public function testViewArrayAccess(): void
    {
        $view = $this->getView(['foo' => 'bar']);
        $this->assertInstanceOf(ArrayAccess::class, $view);
        $this->assertTrue($view->offsetExists('foo'));
        $this->assertEquals($view->offsetGet('foo'), 'bar');
        $view->offsetSet('foo', 'baz');
        $this->assertEquals($view->offsetGet('foo'), 'baz');
        $view->offsetUnset('foo');
        $this->assertFalse($view->offsetExists('foo'));
    }

    public function testViewConstructedWithObjectData(): void
    {
        $view = $this->getView(new DataObjectStub);
        $this->assertInstanceOf(ArrayAccess::class, $view);
        $this->assertTrue($view->offsetExists('foo'));
        $this->assertEquals($view->offsetGet('foo'), 'bar');
        $view->offsetSet('foo', 'baz');
        $this->assertEquals($view->offsetGet('foo'), 'baz');
        $view->offsetUnset('foo');
        $this->assertFalse($view->offsetExists('foo'));
    }

    public function testViewMagicMethods(): void
    {
        $view = $this->getView(['foo' => 'bar']);
        $this->assertTrue(isset($view->foo));
        $this->assertEquals($view->foo, 'bar');
        $view->foo = 'baz';
        $this->assertEquals($view->foo, 'baz');
        $this->assertEquals($view['foo'], $view->foo);
        unset($view->foo);
        $this->assertFalse(isset($view->foo));
        $this->assertFalse($view->offsetExists('foo'));
    }

    public function testViewBadMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method Illuminate\View\View::badMethodCall does not exist.');

        $view = $this->getView();
        $view->badMethodCall();
    }

    public function testViewGatherDataWithRenderable(): void
    {
        $view = $this->getView();
        $view->getFactory()->shouldReceive('incrementRender')->once()->ordered();
        $view->getFactory()->shouldReceive('callComposer')->once()->ordered()->with($view);
        $view->getFactory()->shouldReceive('getShared')->once()->andReturn(['shared' => 'foo']);
        $view->getEngine()->shouldReceive('get')->once()->andReturn('contents');
        $view->getFactory()->shouldReceive('decrementRender')->once()->ordered();
        $view->getFactory()->shouldReceive('flushStateIfDoneRendering')->once();

        $view->renderable = m::mock(Renderable::class);
        $view->renderable->shouldReceive('render')->once()->andReturn('text');
        $this->assertEquals('contents'.PHP_EOL, $view->render());
    }

    public function testViewRenderSections(): void
    {
        $view = $this->getView();
        $view->getFactory()->shouldReceive('incrementRender')->once()->ordered();
        $view->getFactory()->shouldReceive('callComposer')->once()->ordered()->with($view);
        $view->getFactory()->shouldReceive('getShared')->once()->andReturn(['shared' => 'foo']);
        $view->getEngine()->shouldReceive('get')->once()->andReturn('contents');
        $view->getFactory()->shouldReceive('decrementRender')->once()->ordered();
        $view->getFactory()->shouldReceive('flushStateIfDoneRendering')->once();

        $view->getFactory()->shouldReceive('getSections')->once()->andReturn(['foo', 'bar']);
        $sections = $view->renderSections();
        $this->assertEquals($sections[0], 'foo');
        $this->assertEquals($sections[1], 'bar');
    }

    public function testWithErrors(): void
    {
        $view = $this->getView();
        $errors = ['foo' => 'bar', 'qu' => 'ux'];
        $this->assertSame($view, $view->withErrors($errors));
        $this->assertInstanceOf(MessageBag::class, $view->errors);
        $foo = $view->errors->get('foo');
        $this->assertEquals($foo[0], 'bar');
        $qu = $view->errors->get('qu');
        $this->assertEquals($qu[0], 'ux');
        $data = ['foo' => 'baz'];
        $this->assertSame($view, $view->withErrors(new MessageBag($data)));
        $foo = $view->errors->get('foo');
        $this->assertEquals($foo[0], 'baz');
    }

    protected function getView($data = []): View
    {
        return new View(
            m::mock(Factory::class),
            m::mock(Engine::class),
            'view',
            'path',
            $data
        );
    }
}

class DataObjectStub
{
    public $foo = 'bar';
}
