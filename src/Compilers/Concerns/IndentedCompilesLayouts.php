<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns;

use Illuminate\View\Compilers\Concerns\CompilesLayouts;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ExpressionHelper;

trait IndentedCompilesLayouts
{
    use CompilesLayouts;

    /**
     * Compile the yield statements into valid PHP.
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileYield($expression, $indenting = ''): string
    {
        ExpressionHelper::addIndenting($expression, $indenting);
        
        return "<?php echo \$__env->yieldContent{$expression}; ?>";
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * The first parameter is never used, but the first parameter returned by
     * compilesStatement is always the expression matched
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileShow($expression = '', $indenting = ''): string
    {
        return "<?php echo \$__env->yieldSection('" .$indenting. "'); ?>";
    }
}
