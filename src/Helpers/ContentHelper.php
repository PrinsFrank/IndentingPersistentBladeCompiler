<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Helpers;

class ContentHelper
{
    /**
     * This function matches any newline if that line contains content and adds padding
     * in front of the content so no useless padding is added for empty lines
     *
     * @param string $content
     * @param $indenting
     */
    public static function addIndentingEachLine(string &$content, string $indenting): void
    {
        $content = preg_replace('/(\r\n|\r|\n)(.)/', '$1'.$indenting.'$2', $content);
    }

    /**
     * If no newline is found at the end of the string, add a newline character
     * If we don't do this, indenting breaks with the next content
     *
     * @param string $content
     */
    public static function addLastNewLineIfMissing(string &$content): void
    {
        if (!in_array(substr($content,-1), ["\n", "\r", "\r\n"])){
            $content .= PHP_EOL;
        }
    }
}