<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Compilers\Concerns;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Compilers\AbstractBladeTestCase;

class BladeStackTest extends AbstractBladeTestCase
{
    public function testStackIsCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->yieldPushContent(\'foo\', \'\'); ?>', $this->compiler->compileString('@stack(\'foo\')'));
        $this->assertEquals('    <?php echo $__env->yieldPushContent(\'foo\', \'    \'); ?>', $this->compiler->compileString('    @stack(\'foo\')'));
    }
}
