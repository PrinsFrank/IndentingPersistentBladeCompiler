<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade;

class BladeShowTest extends AbstractBladeTestCase
{
    public function testShowsAreCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->yieldSection(\'\'); ?>', $this->compiler->compileString('@show'));
        $this->assertEquals('    <?php echo $__env->yieldSection(\'    \'); ?>', $this->compiler->compileString('    @show'));
    }
}
