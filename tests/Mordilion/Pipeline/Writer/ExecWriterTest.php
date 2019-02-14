<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Writer\ExecWriter;

class ExecWriterTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $writer = new ExecWriter();

        $this->assertTrue($writer->close());
    }

    public function testMethodOpenReturnsTrue()
    {
        $writer = new ExecWriter();

        $this->assertTrue($writer->open());
    }

    public function testMethodWriteExecutesTheCommandAndReturnsCallsTheCallbackMethod()
    {
        $out = null;

        $writer = new ExecWriter();
        $writer->open();
        $writer->setOutputCallback(function (array $output) use (&$out) {
            $out = $output;
        });

        $row = ['cat ' . __DIR__ . '/../../../Data/file.data'];
        $data = file(__DIR__ . '/../../../Data/file.data', FILE_IGNORE_NEW_LINES);

        $writer->write($row);

        $this->assertEquals($out, $data);
        $this->assertTrue($writer->open());
    }
}
