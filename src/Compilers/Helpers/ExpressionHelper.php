<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Compilers\Helpers;

class ExpressionHelper
{
    /**
     * @param string $expression
     * @param string $indenting
     * @param int $index
     * @return string
     */
    public static function addIndenting(string $expression, string $indenting, int $index = -1): string
    {
        return substr_replace($expression, ", '".$indenting."')", $index);
    }
}