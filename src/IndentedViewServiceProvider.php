<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\ViewFinderInterface;
use Illuminate\View\ViewServiceProvider;
use PrinsFrank\IndentingPersistentBladeCompiler\Compilers\IndentedBladeCompiler;

class IndentedViewServiceProvider extends ViewServiceProvider
{
    /**
     * @param EngineResolver $resolver
     *
     * @return void
     */
    public function registerBladeEngine($resolver): void
    {
        // The Compiler engine requires an instance of the CompilerInterface, which in this
        // case will be the IndentedBlade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.
        $this->app->singleton('blade.compiler', function () {
            return new IndentedBladeCompiler(
                $this->app['files'], $this->app['config']['view.compiled']
            );
        });

        $resolver->register('blade', function () {
            return new CompilerEngine($this->app['blade.compiler']);
        });
    }

    /**
     * @param EngineResolver $resolver
     * @param ViewFinderInterface $finder
     * @param Dispatcher $events
     * @return Factory|IndentedViewFactory
     */
    protected function createFactory($resolver, $finder, $events): IndentedViewFactory
    {
        return new IndentedViewFactory($resolver, $finder, $events);
    }
}
