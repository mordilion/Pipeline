<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Reader\IteratorReader;

class IteratorReaderTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $reader = new IteratorReader();

        $this->assertTrue($reader->close());
    }

    public function testMethodCurrentReturnsTheCurrentItem()
    {
        $reader = new IteratorReader();
        $iterator = new LimitIterator(new ArrayIterator(['Item 1', 'Item 2', 'Item 3']), 1);

        $reader->setIterator($iterator);
        $reader->rewind();

        $this->assertEquals('Item 2', $reader->current());

        $reader->next();

        $this->assertEquals('Item 3', $reader->current());
    }

    public function testMethodKeyReturnsTheRightKeyOfTheCurrentItem()
    {
        $reader = new IteratorReader();
        $iterator = new LimitIterator(new ArrayIterator(['11' => 'Item 1', '22' => 'Item 2', '33' => 'Item 3']), 1);

        $reader->setIterator($iterator);
        $reader->rewind();

        $this->assertEquals('22', $reader->key());

        $reader->next();

        $this->assertEquals('33', $reader->key());
    }

    public function testMethodNextMovesTheItemPointer()
    {
        $reader = new IteratorReader();
        $iterator = new ArrayIterator(['11' => 'Item 1', '22' => 'Item 2', '33' => 'Item 3']);

        $reader->setIterator($iterator);
        $reader->rewind();

        $this->assertEquals('Item 1', $reader->current());

        $reader->next();

        $this->assertEquals('Item 2', $reader->current());
    }

    public function testMethodOpenRewindsAndReturnsTrue()
    {
        $reader = new IteratorReader();
        $iterator = new ArrayIterator(['11' => 'Item 1', '22' => 'Item 2', '33' => 'Item 3']);

        $reader->setIterator($iterator);
        $reader->rewind();
        $reader->next();

        $this->assertTrue($reader->open());
        $this->assertEquals('Item 1', $reader->current());
    }

    public function testMethodRewindMovesTheItemPointerToTheFirstItem()
    {
        $reader = new IteratorReader();
        $iterator = new ArrayIterator(['11' => 'Item 1', '22' => 'Item 2', '33' => 'Item 3']);

        $reader->setIterator($iterator);
        $reader->rewind();
        $reader->next();
        $reader->rewind();

        $this->assertEquals('Item 1', $reader->current());
    }

    public function testMethodValidReturnsTrueOrFalseForDifferentStates()
    {
        $reader = new IteratorReader();
        $iterator = new ArrayIterator(['11' => 'Item 1', '22' => 'Item 2']);

        $reader->setIterator($iterator);
        $reader->rewind();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertFalse($reader->valid());
    }
}
