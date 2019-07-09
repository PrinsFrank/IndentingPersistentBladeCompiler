<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Helpers;

class ExpressionHelper
{
    /**
     * @param string $expression
     * @param string $indenting
     * @param bool $quote
     * @return void
     */
    public static function addIndenting(string &$expression, string $indenting, $quote = true): void
    {
        if($quote){
            $expression = substr_replace($expression, ", '".$indenting."')", -1);
            return;
        }
        $expression = substr_replace($expression, ', ' .$indenting. ')', -1);
    }
}
