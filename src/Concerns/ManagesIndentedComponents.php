<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Concerns;

use Illuminate\View\Concerns\ManagesComponents;

trait ManagesIndentedComponents
{
    use ManagesComponents;

    /**
     * Render the current component.
     *
     * @param string $indenting
     * @return string
     */
    public function renderComponent(string $indenting = ''): string
    {
        $name = array_pop($this->componentStack);

        return $this->make($name, $this->componentData($name) + ['indenting' => $indenting])->render();
    }
}
