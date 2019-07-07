<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler;

use Illuminate\View\View;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ContentHelper;

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
            ContentHelper::addIndentingEachLine($content, $this->data['indenting']);
        }

        ContentHelper::addLastNewLineIfMissing($content);

        return $content;
    }
}