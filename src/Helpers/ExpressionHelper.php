<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Helpers;

class ExpressionHelper
{
    /**
     * @param string $expression
     * @param string $indenting
     * @return void
     */
    public static function addIndenting(string &$expression, string $indenting): void
    {
        $expression = substr_replace($expression, ", '".$indenting."')", -1);
    }
}
