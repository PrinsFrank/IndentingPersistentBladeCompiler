<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Blade;

class BladeCommentsTest extends AbstractBladeTestCase
{
    public function testCommentsAreCompiled(): void
    {
        $string = '{{--this is a comment--}}';
        $this->assertEmpty($this->compiler->compileString($string));

        $string = '{{--
this is a comment
--}}';
        $this->assertEmpty($this->compiler->compileString($string));

        $string = sprintf('{{-- this is an %s long comment --}}', str_repeat('extremely ', 1000));
        $this->assertEmpty($this->compiler->compileString($string));
    }

    public function testBladeCodeInsideCommentsIsNotCompiled()
    {
        $string = '{{-- @foreach() --}}';

        $this->assertEmpty($this->compiler->compileString($string));
    }
}
