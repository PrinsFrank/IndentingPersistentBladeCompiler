<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ExpressionHelper;

/**
 * Class ContentHelperTest
 * @package PrinsFrank\IndentingPersistentBladeCompiler\Tests\Unit\Helpers
 *
 * @coversDefaultClass \PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ExpressionHelper
 */
class ExpressionHelperTest extends TestCase
{
    /**
     * @covers ::addIndenting
     */
    public function testAddsIndentingToExpression(): void
    {
        $expression = '()';
        ExpressionHelper::addIndenting($expression, '    ');
        $this->assertEquals("(, '    ')", $expression);

        $expression = "('foo')";
        ExpressionHelper::addIndenting($expression, '    ');
        $this->assertEquals("('foo', '    ')", $expression);
    }
}