<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Reader\ExecReader;

class ExecReaderTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $reader = new ExecReader();

        $this->assertTrue($reader->close());
    }

    public function testMethodCurrentReturnsTheCurrentItem()
    {
        $reader = new ExecReader();

        $reader->setCommand('cat ' . realpath(__DIR__ . '/../../../Data') . '/file.data');
        $reader->open();

        $this->assertEquals('First line', $reader->current());

        $reader->next();

        $this->assertEquals('Second line', $reader->current());
    }

    public function testMethodKeyReturnsTheRightKeyOfTheCurrentItem()
    {
        $reader = new ExecReader();

        $reader->setCommand('cat ' . realpath(__DIR__ . '/../../../Data') . '/file.data');
        $reader->open();

        $this->assertEquals(0, $reader->key());

        $reader->next();

        $this->assertEquals(1, $reader->key());
    }

    public function testMethodNextMovesTheItemPointer()
    {
        $reader = new ExecReader();

        $reader->setCommand('cat ' . realpath(__DIR__ . '/../../../Data') . '/file.data');
        $reader->open();

        $this->assertEquals('First line', $reader->current());

        $reader->next();

        $this->assertEquals('Second line', $reader->current());
    }

    public function testMethodOpenRewindsAndReturnsTrue()
    {
        $reader = new ExecReader();

        $reader->setCommand('cat ' . realpath(__DIR__ . '/../../../Data') . '/file.data');
        $reader->open();
        $reader->next();

        $this->assertTrue($reader->open());
        $this->assertEquals('First line', $reader->current());
    }

    public function testMethodRewindMovesTheItemPointerToTheFirstItem()
    {
        $reader = new ExecReader();

        $reader->setCommand('cat ' . realpath(__DIR__ . '/../../../Data') . '/file.data');
        $reader->open();
        $reader->next();
        $reader->rewind();

        $this->assertEquals('First line', $reader->current());
    }

    public function testMethodValidReturnsTrueOrFalseForDifferentStates()
    {
        $reader = new ExecReader();

        $reader->setCommand('head -n 2 ' . realpath(__DIR__ . '/../../../Data') . '/file.data');
        $reader->open();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertFalse($reader->valid());
    }
}
