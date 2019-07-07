<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns;

use Illuminate\View\Compilers\Concerns\CompilesIncludes;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ExpressionHelper;

trait IndentedCompilesIncludes
{
    use CompilesIncludes;

    /**
     * Compile the each statements into valid PHP.
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileEach($expression, $indenting = ''): string
    {
        ExpressionHelper::addIndenting($expression, $indenting);

        return "<?php echo \$__env->renderEach{$expression}; ?>";
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileInclude($expression, $indenting = ''): string
    {
        $expression = $this->stripParentheses($expression);

        return "<?php echo \$__env->make({$expression}, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']) + ['indenting' => '" . $indenting . "'])->render(); ?>";
    }

    /**
     * Compile the include-if statements into valid PHP.
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileIncludeIf($expression, $indenting = ''): string
    {
        $expression = $this->stripParentheses($expression);

        return "<?php if (\$__env->exists({$expression})) echo \$__env->make({$expression}, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']) + ['indenting' => '" . $indenting . "'])->render(); ?>";
    }

    /**
     * Compile the include-when statements into valid PHP.
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileIncludeWhen($expression, $indenting = ''): string
    {
        $expression = $this->stripParentheses($expression);

        return "<?php echo \$__env->renderWhen($expression, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']) + ['indenting' => '" . $indenting . "']); ?>";
    }

    /**
     * Compile the include-first statements into valid PHP.
     *
     * @param string $expression
     * @param string $indenting
     * @return string
     */
    protected function compileIncludeFirst($expression, $indenting = ''): string
    {
        $expression = $this->stripParentheses($expression);

        return "<?php echo \$__env->first({$expression}, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']) + ['indenting' => '" . $indenting . "'])->render(); ?>";
    }
}