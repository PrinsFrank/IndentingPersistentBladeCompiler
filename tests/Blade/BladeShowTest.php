<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade;

class BladeShowTest extends AbstractBladeTestCase
{
    public function testShowsAreCompiled()
    {
        $this->assertEquals('<?php echo $__env->yieldSection(); ?>', $this->compiler->compileString('@show'));
    }
}
