<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Compilers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns\IndentedCompilesComponents;
use PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns\IndentedCompilesIncludes;
use PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns\IndentedCompilesLayouts;
use PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Concerns\IndentedCompilesStacks;

class IndentedBladeCompiler extends BladeCompiler
{
    use IndentedCompilesComponents,
        IndentedCompilesIncludes,
        IndentedCompilesLayouts,
        IndentedCompilesStacks;

    /**
     * Compile Blade statements that start with "@".
     *
     * @param  string  $value
     * @return string
     */
    public function compileStatements($value): string
    {
        return preg_replace_callback(
            '/\B([\h]*)@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?4) )* \))?/x', function ($match) {
            return $this->compileStatement($match);
        }, $value
        );
    }

    /**
     * Compile a single Blade @ statement.
     *
     * @param  array  $match
     * @return string
     */
    protected function compileStatement($match): string
    {
        if (Str::contains($match[2], '@')) {
            $match[0] = isset($match[4]) ? $match[1].$match[2].$match[4] : $match[1].$match[2];
        } elseif (isset($this->customDirectives[$match[2]])) {
            $match[0] = $match[1].$this->callCustomDirective($match[2], Arr::get($match, 4));
        } elseif (method_exists($this, $method = 'compile'.ucfirst($match[2]))) {
            $match[0] = $match[1].$this->$method(Arr::get($match, 4), $match[1]);
        }

        return isset($match[4]) ? $match[0] : $match[0].$match[3];
    }
}
