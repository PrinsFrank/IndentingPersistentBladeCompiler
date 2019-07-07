<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Concerns;

use Illuminate\Contracts\View\View;
use Illuminate\View\Concerns\ManagesLayouts;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ContentHelper;

trait ManagesIndentedLayouts
{
    use ManagesLayouts;

    /**
     * Get the string contents of a section.
     *
     * @param string $section
     * @param string $default
     * @param string $indenting
     * @return string
     */
    public function yieldContent($section, $default = '', $indenting = ''): string
    {
        // We need to keep the signature the same, so the middle param is now optional
        if(func_num_args() === 2){
            $indenting = $default;
            $default = '';
        }

        $sectionContent = $default instanceof View ? $default : e($default);

        if (isset($this->sections[$section])) {
            $sectionContent = $this->sections[$section];
        }

        $sectionContent = str_replace('@@parent', '--parent--holder--', $sectionContent);
        ContentHelper::cleanEmptyLines($sectionContent);
        ContentHelper::removeIndentingFirstLine($sectionContent, $indenting);

        $content = str_replace(
            '--parent--holder--', '@parent', str_replace(static::parentPlaceholder($section), '', $sectionContent)
        );

        ContentHelper::addIndentingEachLine($content, $indenting);

        return $content;
    }

    /**
     * Stop injecting content into a section and return its contents.
     *
     * @param string $indenting
     * @return string
     */
    public function yieldSection($indenting = ''): string
    {
        if (empty($this->sectionStack)) {
            return '';
        }

        return $this->yieldContent($this->stopSection(), '', $indenting);
    }
}
