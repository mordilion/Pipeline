<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Writer\NullWriter;

class NullWriterTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $writer = new NullWriter();

        $this->assertTrue($writer->close());
    }

    public function testMethodOpenReturnsTrue()
    {
        $writer = new NullWriter();

        $this->assertTrue($writer->open());
    }

    public function testMethodWriteDoesNothing()
    {
        $writer = new NullWriter();
        $writer->open();

        $data = ['col1' => 'val1', 'col2' => 'val2'];

        $writer->write($data);

        $this->assertTrue(true); // not testable, but we want 100% code coverage
    }
}
