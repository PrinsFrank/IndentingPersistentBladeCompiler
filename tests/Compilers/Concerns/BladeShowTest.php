<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Compilers\Concerns;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Compilers\AbstractBladeTestCase;

class BladeShowTest extends AbstractBladeTestCase
{
    public function testShowsAreCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->yieldSection(\'\'); ?>', $this->compiler->compileString('@show'));
        $this->assertEquals('    <?php echo $__env->yieldSection(\'    \'); ?>', $this->compiler->compileString('    @show'));
    }
}
