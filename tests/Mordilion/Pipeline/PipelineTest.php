<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Pipeline;
use Mordilion\Pipeline\Reader\NullReader;
use Mordilion\Pipeline\Writer\NullWriter;

use Mordilion\Configurable\Configuration;
use Mordilion\Configurable\Configuration\Factory as ConfigurationFactory;

class PipelineTest extends TestCase
{
    const ARRAY_READER_CONFIGURATION_JSON = '
            "reader": {
                "class": "Mordilion\\\Pipeline\\\Reader\\\ArrayReader",
                "configuration": {
                    "data": [
                        {"id": 1, "name": "Record 1"},
                        {"id": 2, "name": "Record 2"},
                        {"id": 3, "name": "Record 3"},
                        {"id": 4, "name": "Record 4"},
                        {"id": 5, "name": "Record 5"}
                    ]
                }
            }
        ';

    const ARRAY_WRITER_CONFIGURATION_JSON = '
            "writer": {
                "class": "Mordilion\\\Pipeline\\\Writer\\\ArrayWriter",
                "configuration": {}
            }
        ';


    public function testWithNullReaderAndNullWriter()
    {
        $pipeline = new Pipeline();

        $reader = new NullReader();
        $writer = new NullWriter();

        $pipeline = new Pipeline();
        $pipeline->setReader($reader)
            ->setWriter($writer)
            ->transfer();

        $this->assertSame($reader, $pipeline->getReader());
        $this->assertSame($writer, $pipeline->getWriter());
    }

    public function testFullConfigurationWithArrayReaderAndArrayWriter()
    {
        $json = '{' . self::ARRAY_READER_CONFIGURATION_JSON . ', ' . self::ARRAY_WRITER_CONFIGURATION_JSON . '}';

        $pipeline = new Pipeline();
        $pipeline->setConfiguration(ConfigurationFactory::create($json, 'json'));
        $pipeline->transfer();

        $this->assertEquals($pipeline->getReader()->getData(), $pipeline->getWriter()->getData());
    }

    public function testFullConfigurationWithArrayReaderAndArrayWriterAndCallbackMethod()
    {
        $json = '{' . self::ARRAY_READER_CONFIGURATION_JSON . ', ' . self::ARRAY_WRITER_CONFIGURATION_JSON . '}';

        $pipeline = new Pipeline();
        $pipeline->setConfiguration(ConfigurationFactory::create($json, 'json'));
        $pipeline->transfer(function (array $row) {
            return array_merge($row, ['exported_at' => date('Y-m-d H:i:s')]);
        });

        $this->assertNotEquals($pipeline->getReader()->getData(), $pipeline->getWriter()->getData());

        $data = $pipeline->getWriter()->getData();
        $this->assertCount(5, $data);

        foreach ($data as $row) {
            $this->assertArrayHasKey('exported_at', $row);
        }
    }

    public function testExportThrowsExceptionIfReaderIsNotDefined()
    {
        $json = '{' . self::ARRAY_WRITER_CONFIGURATION_JSON . '}';
        
        $pipeline = new Pipeline();
        $pipeline->setConfiguration(ConfigurationFactory::create($json, 'json'));

        try {
            $pipeline->transfer();
            throw new \Exception('');
        } catch (\InvalidArgumentException $exception) {
            $this->assertTrue(strpos($exception->getMessage(), 'reader') !== false);
        } catch (\Exception $exception) {
            $this->fail('Expected exception "InvalidArgumentException" was not thrown.');
        }
    }

    public function testExportThrowsExceptionIfWriterIsNotDefined()
    {
        $json = '{' . self::ARRAY_READER_CONFIGURATION_JSON . '}';
        
        $pipeline = new Pipeline();
        $pipeline->setConfiguration(ConfigurationFactory::create($json, 'json'));

        try {
            $pipeline->transfer();
            throw new \Exception('');
        } catch (\InvalidArgumentException $exception) {
            $this->assertTrue(strpos($exception->getMessage(), 'writer') !== false);
        } catch (\Exception $exception) {
            $this->fail('Expected exception "InvalidArgumentException" was not thrown.');
        }
    }

    public function testSetReaderThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pipeline = new Pipeline();
        $pipeline->setReader('not a valid reader');
    }

    public function testSetWriterThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pipeline = new Pipeline();
        $pipeline->setWriter('not a valid writer');
    }

    public function testAnalyzeConfigurationThrowsRuntimeExceptionIfAWrongClassIsProvided()
    {
        $this->expectException(\RuntimeException::class);

        $pipeline = new Pipeline();
        $pipeline->setWriter([
            'class' => \InvalidArgumentException::class,
            'configuration' => []
        ]);
    }

    public function testAnalyzeConfigurationThrowsRuntimeExceptionIfAWrongFormatIsProvided1()
    {
        $this->expectException(\RuntimeException::class);

        $pipeline = new Pipeline();
        $pipeline->setWriter([
            'class-wrong' => \InvalidArgumentException::class,
            'configuration' => []
        ]);
    }

    public function testAnalyzeConfigurationThrowsRuntimeExceptionIfAWrongFormatIsProvided2()
    {
        $this->expectException(\RuntimeException::class);

        $pipeline = new Pipeline();
        $pipeline->setWriter([
            'class' => NullWriter::class,
            'configuration-wrong' => []
        ]);
    }
}
