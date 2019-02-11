<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Reader\ResourceReader;

class ResourceReaderTest extends TestCase
{
    public function testMethodCloseReturnsTrueWithResource()
    {
        $reader = new ResourceReader();
        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $this->assertTrue($reader->close());
    }

    public function testMethodCloseReturnsTrueWithoutResource()
    {
        $reader = new ResourceReader();

        $this->assertTrue($reader->close());
    }

    public function testMethodCurrentReturnsTheCurrentItem()
    {
        $reader = new ResourceReader();
        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();
        $reader->next();

        $row = $reader->current();

        $this->assertEquals("Second line\n", $row);
    }

    public function testMethodKeyReturnsTheRightKeyOfTheCurrentItem()
    {
        $reader = new ResourceReader();
        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();

        $this->assertEquals(0, $reader->key());

        $reader->next();

        $this->assertEquals(1, $reader->key());
    }

    public function testMethodNextMovesTheItemPointer()
    {
        $reader = new ResourceReader();
        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();

        $row = $reader->current();

        $this->assertEquals("First line\n", $row);

        $reader->next();

        $row = $reader->current();

        $this->assertEquals("Second line\n", $row);
    }

    public function testMethodOpenRewindsAndReturnsTrue()
    {
        $reader = new ResourceReader();
        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $this->assertTrue($reader->open());

        $reader->next();

        $row = $reader->current();

        $this->assertEquals("Second line\n", $row);
    }

    public function testMethodRewindMovesTheItemPointerToTheFirstItem()
    {
        $reader = new ResourceReader();
        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();
        $reader->next();
        $reader->rewind();

        $row = $reader->current();

        $this->assertEquals("First line\n", $row);
    }

    public function testMethodValidReturnsTrueOrFalseForDifferentStates()
    {
        $reader = new ResourceReader();
        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $reader->open();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertTrue($reader->valid());

        for ($i = 1; $i <= 4; $i++) {
            $reader->next();
        }

        $this->assertFalse($reader->valid());
    }

    public function testMethodSetResourceThrowsAnExceptionForAWrongParameter()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new ResourceReader();

        $reader->setResource(new \DateTime());
    }

    public function testMethodSetResourceCreatesItsOwnFOpenResourceByArray()
    {
        $reader = new ResourceReader();

        $reader->setResource([ResourceReader::TYPE_FOPEN, realpath(__DIR__ . '/../../../Data') . '/file.data', 'r']);

        $this->assertTrue(is_resource($reader->getResource()));
    }

    public function testMethodSetResourceCreatesItsOwnFSockOpenResourceByArray()
    {
        $reader = new ResourceReader();

        $reader->setResource([ResourceReader::TYPE_FSOCKOPEN, 'www.google.com', 80, 30]);

        $this->assertTrue(is_resource($reader->getResource()));
    }

    public function testMethodOpenThrowsExceptionIfPdoIsNotDefined()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new ResourceReader();

        $reader->open();
    }
}
