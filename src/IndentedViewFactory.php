<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler;

use Illuminate\Support\Str;
use Illuminate\View\Factory;
use PrinsFrank\IndentingPersistentBladeCompiler\Concerns\ManagesIndentedComponents;
use PrinsFrank\IndentingPersistentBladeCompiler\Concerns\ManagesIndentedLayouts;
use PrinsFrank\IndentingPersistentBladeCompiler\Concerns\ManagesIndentedStacks;

class IndentedViewFactory extends Factory
{
    use ManagesIndentedComponents,
        ManagesIndentedLayouts,
        ManagesIndentedStacks;

    protected function viewInstance($view, $path, $data): IndentedView
    {
        return new IndentedView($this, $this->getEngineFromPath($path), $view, $path, $data);
    }

    /**
     * @param string $view
     * @param array $data
     * @param string $iterator
     * @param string $indenting
     * @param string $empty
     * @return bool|string
     */
    public function renderEach($view, $data, $iterator, $empty = 'raw|', $indenting = null)
    {
        $result = '';

        // Because the function has to stay compatible with the default factory function so they stay interchangeable
        // without the need to clear the cache when switching and the third parameter is optional and the fourth
        // is always given we need to move the parameter one spot if the optional $empty parameter is not set
        if(func_num_args() === 4){
            $indenting = $empty;
            $empty = 'raw|';
        }

        // If is actually data in the array, we will loop through the data and append
        // an instance of the partial view to the final result HTML passing in the
        // iterated value of this data array, allowing the views to access them.
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                // After the first include which already has the indenting prepended
                // we need to add the indenting to every subsequent first line
                if($key !== array_key_first($data)){
                    $result .= $indenting;
                }
                $result .= $this->make(
                    $view,
                    ['key' => $key, $iterator => $value, 'indenting' => $indenting]
                )->render();
            }
        }

        // If there is no data in the array, we will render the contents of the empty
        // view. Alternatively, the "empty view" could be a raw string that begins
        // with "raw|" for convenience and to let this know that it is a string.
        else {
            $result = Str::startsWith($empty, 'raw|')
                ? substr($empty, 4)
                : $this->make($empty, ['indenting' => $indenting])->render();
        }

        return $result;
    }
}
