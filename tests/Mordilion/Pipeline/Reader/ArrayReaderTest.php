<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Reader\ArrayReader;

class ArrayReaderTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $reader = new ArrayReader();

        $this->assertTrue($reader->close());
    }

    public function testMethodCurrentReturnsTheCurrentItem()
    {
        $reader = new ArrayReader();
        $data = ['Item 1', 'Item 2'];

        $reader->setData($data);

        $this->assertEquals('Item 1', $reader->current());

        $reader->next();

        $this->assertEquals('Item 2', $reader->current());
    }

    public function testMethodKeyReturnsTheRightKeyOfTheCurrentItem()
    {
        $reader = new ArrayReader();
        $data = ['11' => 'Item 1', '22' => 'Item 2'];

        $reader->setData($data);

        $this->assertEquals('11', $reader->key());

        $reader->next();

        $this->assertEquals('22', $reader->key());
    }

    public function testMethodNextMovesTheItemPointer()
    {
        $reader = new ArrayReader();
        $data = ['Item 1', 'Item 2'];

        $reader->setData($data);

        $this->assertEquals('Item 1', $reader->current());

        $reader->next();

        $this->assertEquals('Item 2', $reader->current());
    }

    public function testMethodOpenRewindsAndReturnsTrue()
    {
        $reader = new ArrayReader();
        $data = ['Item 1', 'Item 2'];

        $reader->setData($data);
        $reader->next();

        $this->assertTrue($reader->open());
        $this->assertEquals('Item 1', $reader->current());
    }

    public function testMethodRewindMovesTheItemPointerToTheFirstItem()
    {
        $reader = new ArrayReader();
        $data = ['Item 1', 'Item 2'];

        $reader->setData($data);
        $reader->next();
        $reader->rewind();

        $this->assertEquals('Item 1', $reader->current());
    }

    public function testMethodValidReturnsTrueOrFalseForDifferentStates()
    {
        $reader = new ArrayReader();
        $data = ['Item 1', 'Item 2'];

        $reader->setData($data);

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertFalse($reader->valid());
    }
}
