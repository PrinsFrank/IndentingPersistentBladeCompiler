<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler;

use Illuminate\View\Factory;

class IndentedViewFactory extends Factory
{
    protected function viewInstance($view, $path, $data): IndentedView
    {
        return new IndentedView($this, $this->getEngineFromPath($path), $view, $path, $data);
    }
}