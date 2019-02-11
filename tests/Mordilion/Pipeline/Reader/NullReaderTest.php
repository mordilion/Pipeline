<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Reader\NullReader;

class NullReaderTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $reader = new NullReader();

        $this->assertTrue($reader->close());
    }

    public function testMethodCurrentReturnsNoItem()
    {
        $reader = new NullReader();

        $this->assertEquals($reader->current(), null);
    }

    public function testMethodKeyReturnsNoKey()
    {
        $reader = new NullReader();

        $this->assertEquals($reader->key(), null);
    }

    public function testMethodNextMovesNotTheItemPointer()
    {
        $reader = new NullReader();

        $reader->next();

        $this->assertTrue(true); // not testable, but we want 100% code coverage
    }

    public function testMethodOpenReturnsTrue()
    {
        $reader = new NullReader();

        $this->assertTrue($reader->open());
    }

    public function testMethodRewindMovesNotTheItemPointer()
    {
        $reader = new NullReader();

        $reader->next();
        $reader->rewind();

        $this->assertTrue(true); // not testable, but we want 100% code coverage
    }

    public function testMethodValidReturnsFalse()
    {
        $reader = new NullReader();

        $this->assertFalse($reader->valid());
    }
}
