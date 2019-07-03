<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests;

use stdClass;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Illuminate\View\Engines\EngineResolver;

class ViewEngineResolverTest extends TestCase
{
    public function testResolversMayBeResolved(): void
    {
        $resolver = new EngineResolver;
        $resolver->register('foo', function () {
            return new stdClass;
        });
        $result = $resolver->resolve('foo');

        $this->assertEquals(spl_object_hash($result), spl_object_hash($resolver->resolve('foo')));
    }

    public function testResolverThrowsExceptionOnUnknownEngine(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $resolver = new EngineResolver;
        $resolver->resolve('foo');
    }
}
