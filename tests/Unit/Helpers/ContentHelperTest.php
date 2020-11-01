<?php

namespace PrinsFrank\IndentingPersistentBladeCompiler\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ContentHelper;

/**
 * Class ContentHelperTest
 * @package PrinsFrank\IndentingPersistentBladeCompiler\Tests\Helpers
 *
 * @coversDefaultClass \PrinsFrank\IndentingPersistentBladeCompiler\Helpers\ContentHelper
 */
class ContentHelperTest extends TestCase
{
    /**
     * @covers ::addIndentingEachLine
     */
    public function testAddsIndentingEachLine(): void
    {
        $content = 'foo';
        ContentHelper::addIndentingEachLine($content, '    ');
        $this->assertEquals('foo', $content);

        $content = 'foo'.PHP_EOL.'bar';
        ContentHelper::addIndentingEachLine($content, '    ');
        $this->assertEquals('foo'.PHP_EOL.'    bar', $content);

        $content = 'foo'.PHP_EOL.'bar'.PHP_EOL.'foo';
        ContentHelper::addIndentingEachLine($content, '    ');
        $this->assertEquals('foo'.PHP_EOL.'    bar'.PHP_EOL.'    foo', $content);
    }

    /**
     * @covers ::addLastNewLineIfMissing
     */
    public function testAddsLastLineIfMissing(): void
    {
        $content = 'foo';
        ContentHelper::addLastNewLineIfMissing($content);
        $this->assertEquals('foo'.PHP_EOL, $content);

        $content = 'foo'.PHP_EOL.'bar';
        ContentHelper::addLastNewLineIfMissing($content);
        $this->assertEquals('foo'.PHP_EOL.'bar'.PHP_EOL, $content);

        $content = 'foo'.PHP_EOL;
        ContentHelper::addLastNewLineIfMissing($content);
        $this->assertEquals('foo'.PHP_EOL, $content);
    }

    /**
     * @covers ::cleanEmptyLines
     */
    public function testCleanEmptyLines(): void
    {
        $content = 'foo'.PHP_EOL.'bar';
        ContentHelper::cleanEmptyLines($content);
        $this->assertEquals('foo'.PHP_EOL.'bar', $content);

        $content = 'foo'.PHP_EOL.'    '.PHP_EOL.'bar';
        ContentHelper::cleanEmptyLines($content);
        $this->assertEquals('foo'.PHP_EOL.'bar', $content);
    }

    /**
     * @covers ::removeIndentingFirstLine
     */
    public function testRemovesIndentingFirstLine(): void
    {
        $content = '    foo'.PHP_EOL.'bar';
        ContentHelper::removeIndentingFirstLine($content, '    ');
        $this->assertEquals('foo'.PHP_EOL.'bar', $content);

        $content = 'foo'.PHP_EOL.'bar';
        ContentHelper::removeIndentingFirstLine($content, '    ');
        $this->assertEquals('foo'.PHP_EOL.'bar', $content);

        $content = '    foo'.PHP_EOL.'    bar';
        ContentHelper::removeIndentingFirstLine($content, '    ');
        $this->assertEquals('foo'.PHP_EOL.'    bar', $content);

        $content = 'foo'.PHP_EOL.'    bar';
        ContentHelper::removeIndentingFirstLine($content, '    ');
        $this->assertEquals('foo'.PHP_EOL.'    bar', $content);
    }
}