<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Compilers\Concerns;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Compilers\AbstractBladeTestCase;

class BladeStackTest extends AbstractBladeTestCase
{
    public function testStackIsCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->yieldPushContent(\'foo\', \'\'); ?>', $this->compiler->compileString('@stack(\'foo\')'));
        $this->assertEquals('    <?php echo $__env->yieldPushContent(\'foo\', \'    \'); ?>', $this->compiler->compileString('    @stack(\'foo\')'));
    }
}
