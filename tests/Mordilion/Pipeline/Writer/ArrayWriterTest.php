<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Writer\ArrayWriter;

class ArrayWriterTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $writer = new ArrayWriter();

        $this->assertTrue($writer->close());
    }

    public function testMethodOpenReturnsTrue()
    {
        $writer = new ArrayWriter();

        $this->assertTrue($writer->open());
    }

    public function testMethodOpenClearsTheInternalDataArray()
    {
        $writer = new ArrayWriter();
        $writer->open();

        $data = ['col1' => 'val1', 'col2' => 'val2'];

        $writer->write($data);

        $this->assertEquals([$data], $writer->getData());
        $this->assertTrue($writer->open());
        $this->assertEmpty($writer->getData());
    }

    public function testMethodGetDataReturnsTheCurrentDataArray()
    {
        $writer = new ArrayWriter();
        $writer->open();

        $data = ['col1' => 'val1', 'col2' => 'val2'];

        $writer->write($data);

        $this->assertEquals([$data], $writer->getData());
    }

    public function testMethodWriteStoresTheProvidedDataInTheInternalDataArray()
    {
        $writer = new ArrayWriter();
        $writer->open();

        $data = ['col1' => 'val1', 'col2' => 'val2'];

        $writer->write($data);

        $this->assertEquals([$data], $writer->getData());
    }
}
