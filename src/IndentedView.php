<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler;

use Illuminate\View\View;

class IndentedView extends View
{
    /**
     * Get the evaluated contents of the view.
     *
     * @return string
     */
    protected function getContents(): string
    {
        $content = $this->engine->get($this->path, $this->gatherData());

        if(isset($this->data['indenting'])){
            $content = preg_replace('/(\r\n|\r|\n)/', '$1'.$this->data['indenting'], $content);
        }

        if (!in_array(substr($content,-1), ["\n", "\r", "\r\n"])){
            $content .= PHP_EOL;
        }

        return $content;
    }
}