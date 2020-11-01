<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Compilers\Concerns;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Compilers\AbstractBladeTestCase;

class BladeIncludeWhenTest extends AbstractBladeTestCase
{
    public function testIncludeWhensAreCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->renderWhen(true, \'foo\', ["foo" => "bar"], \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'\']); ?>', $this->compiler->compileString('@includeWhen(true, \'foo\', ["foo" => "bar"])'));
        $this->assertEquals('    <?php echo $__env->renderWhen(true, \'foo\', ["foo" => "bar"], \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'    \']); ?>', $this->compiler->compileString('    @includeWhen(true, \'foo\', ["foo" => "bar"])'));
        $this->assertEquals('<?php echo $__env->renderWhen(true, \'foo\', \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'\']); ?>', $this->compiler->compileString('@includeWhen(true, \'foo\')'));
        $this->assertEquals('    <?php echo $__env->renderWhen(true, \'foo\', \Illuminate\Support\Arr::except(get_defined_vars(), [\'__data\', \'__path\']) + [\'indenting\' => \'    \']); ?>', $this->compiler->compileString('    @includeWhen(true, \'foo\')'));
    }
}
