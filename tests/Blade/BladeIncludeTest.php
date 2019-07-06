<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade;

class BladeIncludeTest extends AbstractBladeTestCase
{
    public function testIncludesAreCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->make(\'foo\', \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'\'])->render(); ?>', $this->compiler->compileString('@include(\'foo\')'));
        $this->assertEquals('    <?php echo $__env->make(\'foo\', \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'    \'])->render(); ?>', $this->compiler->compileString('    @include(\'foo\')'));
        $this->assertEquals('<?php echo $__env->make(name(foo), \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'\'])->render(); ?>', $this->compiler->compileString('@include(name(foo))'));
        $this->assertEquals('    <?php echo $__env->make(name(foo), \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'    \'])->render(); ?>', $this->compiler->compileString('    @include(name(foo))'));
    }
}
