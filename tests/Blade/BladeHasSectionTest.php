<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade;

class BladeHasSectionTest extends AbstractBladeTestCase
{
    public function testHasSectionStatementsAreCompiled()
    {
        $string = '@hasSection("section")
breeze
@endif';
        $expected = '<?php if (! empty(trim($__env->yieldContent("section")))): ?>
breeze
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
