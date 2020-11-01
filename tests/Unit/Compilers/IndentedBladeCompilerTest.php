<?php

namespace Illuminate\Tests\View;

use PrinsFrank\IndentingPersistentBladeCompiler\Tests\Compilers\AbstractBladeTestCase;

class IndentedBladeCompilerTest extends AbstractBladeTestCase
{
    public function testCompilesCustomDirectives(): void
    {
        $this->compiler->directive('customDirective',
            static function($params){
                return '<?php echo json_encode(' . $params . ') ?>';
            }
        );
        $this->assertEquals('<?php echo json_encode([\'foo\' => \'bar\']) ?>', $this->compiler->compileString('@customDirective([\'foo\' => \'bar\'])'));
    }
}
