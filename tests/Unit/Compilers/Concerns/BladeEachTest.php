<?php

namespace Illuminate\Tests\View\Blade;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Compilers\AbstractBladeTestCase;

class BladeEachTest extends AbstractBladeTestCase
{
    public function testShowEachAreCompiled(): void
    {
        $this->assertEquals('<?php echo $__env->renderEach(\'foo\', [\'1,2,3\'], \'index\', \'\'); ?>', $this->compiler->compileString('@each(\'foo\', [\'1,2,3\'], \'index\')'));
        $this->assertEquals('    <?php echo $__env->renderEach(\'foo\', [\'1,2,3\'], \'index\', \'    \'); ?>', $this->compiler->compileString('    @each(\'foo\', [\'1,2,3\'], \'index\')'));
        $this->assertEquals('<?php echo $__env->renderEach(\'foo\', [\'1,2,3\'], \'index\', \'bar\', \'\'); ?>', $this->compiler->compileString('@each(\'foo\', [\'1,2,3\'], \'index\', \'bar\')'));
        $this->assertEquals('    <?php echo $__env->renderEach(\'foo\', [\'1,2,3\'], \'index\', \'bar\', \'    \'); ?>', $this->compiler->compileString('    @each(\'foo\', [\'1,2,3\'], \'index\', \'bar\')'));

        $this->assertEquals('<?php echo $__env->renderEach(\'foo\', $items, \'item\', \'\'); ?>', $this->compiler->compileString('@each(\'foo\', $items, \'item\')'));
        $this->assertEquals('    <?php echo $__env->renderEach(\'foo\', $items, \'item\', \'    \'); ?>', $this->compiler->compileString('    @each(\'foo\', $items, \'item\')'));
        $this->assertEquals('<?php echo $__env->renderEach(\'foo\', $items, \'item\', \'bar\', \'\'); ?>', $this->compiler->compileString('@each(\'foo\', $items, \'item\', \'bar\')'));
        $this->assertEquals('    <?php echo $__env->renderEach(\'foo\', $items, \'item\', \'bar\', \'    \'); ?>', $this->compiler->compileString('    @each(\'foo\', $items, \'item\', \'bar\')'));
    }
}
