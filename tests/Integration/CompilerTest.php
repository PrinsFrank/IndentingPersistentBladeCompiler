<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Integration;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\Compilers\IndentedBladeCompiler;
use PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewFactory;

/**
 * @coversNothing
 */
class CompilerTest extends TestCase
{
    private const TEMPLATE_PATHS = [__DIR__ . '/fixtures'];
    private const PATH_OUT = __DIR__ . '/out';

    public function testIndentedCompilerResult(): void
    {
        static::assertEquals(
            '<html>' . PHP_EOL .
            '    <head>' . PHP_EOL .
            '        <stack></stack>' . PHP_EOL .
            '        <stack></stack>' . PHP_EOL .
            '    </head>' . PHP_EOL .
            '    <body>' . PHP_EOL .
            '        <sidebar>' . PHP_EOL .
            '            <sidebarcontent></sidebarcontent>' . PHP_EOL .
            '            <sidebarsectioncontent></sidebarsectioncontent>' . PHP_EOL .
            '        </sidebar>' . PHP_EOL .
            '        <container>' . PHP_EOL .
            '            <level-1>' . PHP_EOL .
            '                <include>' . PHP_EOL .
            '                </include>' . PHP_EOL .
            '                <include>' . PHP_EOL .
            '                </include>' . PHP_EOL .
            '                <include>' . PHP_EOL .
            '                </include>' . PHP_EOL .
            '                <include>' . PHP_EOL .
            '                </include>' . PHP_EOL .
            '                <level-2>' . PHP_EOL .
            '                    <level-3>' . PHP_EOL .
            '                        <include>' . PHP_EOL .
            '                        </include>' . PHP_EOL .
            '                        <include>' . PHP_EOL .
            '                        </include>' . PHP_EOL .
            '                    </level-3>' . PHP_EOL .
            '                </level-2>' . PHP_EOL .
            '                <wrapper-component>' . PHP_EOL .
            '                    <wrapper-title>' . PHP_EOL .
            '                        <title></title>' . PHP_EOL .
            '                        <title></title>' . PHP_EOL .
            '                    </wrapper-title>' . PHP_EOL .
            '                    <component></component>' . PHP_EOL .
            '                        <component></component>' . PHP_EOL .
            '                </wrapper-component>' . PHP_EOL .
            '            </level-1>' . PHP_EOL .
            '        </container>' . PHP_EOL .
            '    </body>' . PHP_EOL .
            '</html>' . PHP_EOL,
            $this->renderTemplate( 'main')
        );
    }

    public function testDefaultCompilerResult(): void
    {
        static::assertEquals(
            '<html>' . PHP_EOL .
            '    <head>' . PHP_EOL .
            '        <stack></stack>' . PHP_EOL .
            '<stack></stack>' . PHP_EOL .
            '    </head>' . PHP_EOL .
            '    <body>' . PHP_EOL .
            '        <sidebar>' . PHP_EOL .
            '                        <sidebarcontent></sidebarcontent>' . PHP_EOL .
            '            ' . PHP_EOL .
            '<sidebarsectioncontent></sidebarsectioncontent>' . PHP_EOL .
            '        </sidebar>' . PHP_EOL .
            '        <container>' . PHP_EOL .
            '            <level-1>' . PHP_EOL .
            '    <include>' . PHP_EOL .
            '</include>    <include>' . PHP_EOL .
            '</include>    <include>' . PHP_EOL .
            '</include>    <include>' . PHP_EOL .
            '</include>    <level-2>' . PHP_EOL .
            '        <level-3>' . PHP_EOL .
            '            <include>' . PHP_EOL .
            '</include><include>' . PHP_EOL .
            '</include>        </level-3>' . PHP_EOL .
            '    </level-2>' . PHP_EOL .
            '    <wrapper-component>' . PHP_EOL .
            '    <wrapper-title>' . PHP_EOL .
            '        <title></title>' . PHP_EOL .
            '        <title></title>' . PHP_EOL .
            '    </wrapper-title>' . PHP_EOL .
            '    <component></component>' . PHP_EOL .
            '        <component></component>' . PHP_EOL .
            '</wrapper-component></level-1>' . PHP_EOL .
            '        </container>' . PHP_EOL .
            '    </body>' . PHP_EOL .
            '</html>',
            $this->renderTemplate( 'main', [], false)
        );
    }

    private function renderTemplate(string $viewName, array $templateData = [], $useIndentedCompiler = true): string
    {
        $fileSystem = new Filesystem();
        if ($useIndentedCompiler) {
            $bladeCompiler = new IndentedBladeCompiler($fileSystem, self::PATH_OUT);
        }else {
            $bladeCompiler = new BladeCompiler($fileSystem, self::PATH_OUT);
        }

        $viewResolver = new EngineResolver();
        $viewResolver->register('blade', function () use ($bladeCompiler) {return new CompilerEngine($bladeCompiler);});
        $viewResolver->register('php', function () {return new PhpEngine;});

        if ($useIndentedCompiler) {
            $viewFactory = new IndentedViewFactory($viewResolver, new FileViewFinder($fileSystem, self::TEMPLATE_PATHS), new Dispatcher(new Container()));
        } else {
            $viewFactory = new Factory($viewResolver, new FileViewFinder($fileSystem, self::TEMPLATE_PATHS), new Dispatcher(new Container()));
        }

        $renderedTemplate = $viewFactory->make($viewName, $templateData)->render();
        return str_replace("\r", "", $renderedTemplate); // newlines are not handled properly on WSL.
    }
}