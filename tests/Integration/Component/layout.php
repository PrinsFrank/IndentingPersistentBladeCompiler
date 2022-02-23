<?php
declare(strict_types=1);

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Integration\Component;

use Illuminate\View\Component;

class layout extends Component
{
    public function render()
    {
        return view('components.layout');
    }
}
