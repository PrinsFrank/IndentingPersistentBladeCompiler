<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade;

class BladeAppendTest extends AbstractBladeTestCase
{
    public function testAppendSectionsAreCompiled(): void
    {
        $this->assertEquals('<?php $__env->appendSection(); ?>', $this->compiler->compileString('@append'));
    }
}
