<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Concerns;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\Concerns\ManagesComponents;
use Illuminate\View\View;
use Throwable;

trait ManagesIndentedComponents
{
    use ManagesComponents;

    /**
     * Render the current component.
     *
     * @param string $indenting
     * @return string
     * @throws Throwable
     */
    public function renderComponent(string $indenting = ''): string
    {
        $view = array_pop($this->componentStack);

        $data = $this->componentData();

        if ($view instanceof Closure) {
            $view = $view($data + ['indenting' => $indenting]);
        }

        if ($view instanceof View) {
            return $view->with($data + ['indenting' => $indenting])->render();
        } elseif ($view instanceof Htmlable) {
            return $view->toHtml();
        } else {
            return $this->make($view, $data + ['indenting' => $indenting])->render();
        }
    }
}
