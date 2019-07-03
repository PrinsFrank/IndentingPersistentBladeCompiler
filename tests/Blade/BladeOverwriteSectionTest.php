<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade;

class BladeOverwriteSectionTest extends AbstractBladeTestCase
{
    public function testOverwriteSectionsAreCompiled()
    {
        $this->assertEquals('<?php $__env->stopSection(true); ?>', $this->compiler->compileString('@overwrite'));
    }
}
