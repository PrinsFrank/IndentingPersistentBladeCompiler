<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns;

use Illuminate\View\Compilers\Concerns\CompilesComponents;

trait IndentedCompilesComponents
{
    use CompilesComponents;

    /**
     * Compile the end-component statements into valid PHP.
     * The unused parameter is the blade compiler default return value
     *
     * @param string $indenting
     * @return string
     */
    protected function compileEndComponent($expression = null, $indenting = ''): string
    {
        return "<?php echo \$__env->renderComponent('" . $indenting . "'); ?>";
    }

    /**
     * Compile the end-component-first statements into valid PHP.
     * The unused parameter is the blade compiler default return value
     *
     * @param null $expression
     * @param string $indenting
     * @return string
     */
    protected function compileEndComponentFirst($expression = null, $indenting = ''): string
    {
        return $this->compileEndComponent($indenting);
    }
}
