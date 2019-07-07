<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Helpers;

class ContentHelper
{
    /**
     * @param string $content
     * @param $indenting
     */
    public static function addIndentingEachLine(string &$content, string $indenting): void
    {
        $content = preg_replace('/(\r\n|\r|\n)/', '$1'.$indenting, $content);
    }

    /**
     * @param string $content
     */
    public static function addLastNewLineIfMissing(string &$content): void
    {
        if (!in_array(substr($content,-1), ["\n", "\r", "\r\n"])){
            $content .= PHP_EOL;
        }
    }
}