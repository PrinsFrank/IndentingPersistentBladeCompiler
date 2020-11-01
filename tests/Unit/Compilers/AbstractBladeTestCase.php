<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Compilers;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Filesystem\Filesystem;
use PrinsFrank\IndentingPersistentBladeCompiler\Compilers\IndentedBladeCompiler;

abstract class AbstractBladeTestCase extends TestCase
{
    /** @var IndentedBladeCompiler */
    protected $compiler;

    protected function setUp(): void
    {
        $this->compiler = new IndentedBladeCompiler(m::mock(Filesystem::class), __DIR__);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }
}
