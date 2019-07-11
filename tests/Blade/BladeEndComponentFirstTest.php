<?php

namespace Illuminate\Tests\View\Blade;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade\AbstractBladeTestCase;

class BladeEndComponentFirstTest extends AbstractBladeTestCase
{
    public function testEndComponentFirstsAreCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->renderComponent(\'\'); ?>', $this->compiler->compileString('@endcomponentfirst()'));
        $this->assertEquals('    <?php echo $__env->renderComponent(\'    \'); ?>', $this->compiler->compileString('    @endcomponentfirst()'));
    }
}
