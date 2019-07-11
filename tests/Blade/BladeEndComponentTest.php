<?php

namespace Illuminate\Tests\View\Blade;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade\AbstractBladeTestCase;

class BladeEndComponentTest extends AbstractBladeTestCase
{
    public function testEndComponentsAreCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->renderComponent(\'\'); ?>', $this->compiler->compileString('@endcomponent()'));
        $this->assertEquals('    <?php echo $__env->renderComponent(\'    \'); ?>', $this->compiler->compileString('    @endcomponent()'));
    }
}
