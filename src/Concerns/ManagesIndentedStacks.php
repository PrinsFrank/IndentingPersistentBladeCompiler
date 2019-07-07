<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Concerns;

use Illuminate\View\Concerns\ManagesStacks;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ContentHelper;

trait ManagesIndentedStacks
{
    use ManagesStacks;

    /**
     * Get the string contents of a push section.
     *
     * @param string $section
     * @param string $default
     * @param string $indenting
     * @return string
     */
    public function yieldPushContent($section, $default = '', $indenting = ''): string
    {
        // We need to keep the signature the same, so the middle param is now optional
        if(func_num_args() === 2){
            $indenting = $default;
            $default = '';
        }

        if (! isset($this->pushes[$section]) && ! isset($this->prepends[$section])) {
            return $default;
        }

        $output = '';

        if (isset($this->prepends[$section])) {
            $output .= implode(array_reverse($this->prepends[$section]));
        }

        if (isset($this->pushes[$section])) {
            $output .= implode($this->pushes[$section]);
        }

        ContentHelper::addIndentingEachLine($output, $indenting);
        ContentHelper::addLastNewLineIfMissing($output);

        return $output;
    }
}
