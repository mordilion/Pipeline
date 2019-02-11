<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Reader\SplFileObjectReader;

class SplFileObjectReaderTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $reader = new SplFileObjectReader();

        $this->assertTrue($reader->close());
    }

    public function testMethodCurrentReturnsTheCurrentItem()
    {
        $reader = new SplFileObjectReader();
        $reader->setFile([realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();
        $reader->next();

        $row = $reader->current();

        $this->assertEquals("Second line\n", $row);
    }

    public function testMethodKeyReturnsTheRightKeyOfTheCurrentItem()
    {
        $reader = new SplFileObjectReader();
        $reader->setFile([realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();

        $this->assertEquals(0, $reader->key());

        $reader->next();

        $this->assertEquals(1, $reader->key());
    }

    public function testMethodNextMovesTheItemPointer()
    {
        $reader = new SplFileObjectReader();
        $reader->setFile([realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();

        $row = $reader->current();

        $this->assertEquals("First line\n", $row);

        $reader->next();

        $row = $reader->current();

        $this->assertEquals("Second line\n", $row);
    }

    public function testMethodOpenRewindsAndReturnsTrue()
    {
        $reader = new SplFileObjectReader();
        $reader->setFile([realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $this->assertTrue($reader->open());

        $reader->next();

        $row = $reader->current();

        $this->assertEquals("Second line\n", $row);
    }

    public function testMethodRewindMovesTheItemPointerToTheFirstItem()
    {
        $reader = new SplFileObjectReader();
        $reader->setFile([realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();
        $reader->next();
        $reader->rewind();

        $row = $reader->current();

        $this->assertEquals("First line\n", $row);
    }

    public function testMethodValidReturnsTrueOrFalseForDifferentStates()
    {
        $reader = new SplFileObjectReader();
        $reader->setFile([realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertTrue($reader->valid());

        for ($i = 1; $i <= 4; $i++) {
            $reader->next();
        }

        $this->assertFalse($reader->valid());
    }

    public function testMethodSetFileThrowsAnExceptionForAWrongParameter()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new SplFileObjectReader();

        $reader->setFile(new \DateTime());
    }

    public function testMethodSetFileCreatesItsOwnInstanceByArray()
    {
        $reader = new SplFileObjectReader();

        $reader->setFile([realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $this->assertInstanceOf(\SplFileObject::class, $reader->getFile());
    }

    public function testMethodOpenThrowsExceptionIfPdoIsNotDefined()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new SplFileObjectReader();

        $reader->open();
    }
}
