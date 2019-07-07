<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns;

use Illuminate\View\Compilers\Concerns\CompilesStacks;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ExpressionHelper;

trait IndentedCompilesStacks
{
    use CompilesStacks;

    /**
     * Compile the stack statements into the content.
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileStack($expression, $indenting = ''): string
    {
        ExpressionHelper::addIndenting($expression, $indenting);
        
        return "<?php echo \$__env->yieldPushContent{$expression}; ?>";
    }
}
