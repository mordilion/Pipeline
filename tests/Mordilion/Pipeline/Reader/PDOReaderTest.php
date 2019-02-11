<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Pipeline\Reader\PDOReader;

class PDOReaderTest extends TestCase
{
    public function testMethodCloseReturnsTrue()
    {
        $reader = new PDOReader();

        $this->assertTrue($reader->close());
    }

    public function testMethodCurrentReturnsTheCurrentItem()
    {
        $reader = new PDOReader();

        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'))
            ->setSql('SELECT * FROM demo');

        $reader->open();
        $reader->next();

        $row = $reader->current();

        $this->assertEquals('MultiVersion', $row['name']);
    }

    public function testMethodKeyReturnsTheRightKeyOfTheCurrentItem()
    {
        $reader = new PDOReader();

        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'))
            ->setSql('SELECT * FROM demo');

        $reader->open();

        $this->assertEquals(0, $reader->key());

        $reader->next();

        $this->assertEquals(1, $reader->key());
    }

    public function testMethodNextMovesTheItemPointer()
    {
        $reader = new PDOReader();
        
        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'))
            ->setSql('SELECT * FROM demo');

        $reader->open();

        $row = $reader->current();

        $this->assertEquals('SqLite 3.24.0', $row['name']);

        $reader->next();

        $row = $reader->current();

        $this->assertEquals('MultiVersion', $row['name']);
    }

    public function testMethodOpenRewindsAndReturnsTrue()
    {
        $reader = new PDOReader();

        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'))
            ->setSql('SELECT * FROM demo');

        $this->assertTrue($reader->open());

        $reader->next();

        $row = $reader->current();

        $this->assertEquals('MultiVersion', $row['name']);
    }

    public function testMethodRewindMovesTheItemPointerToTheFirstItem()
    {
        $reader = new PDOReader();
        
        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'))
            ->setSql('SELECT * FROM demo');

        $reader->open();
        $reader->next();
        $reader->rewind();

        $row = $reader->current();

        $this->assertEquals('SqLite 3.24.0', $row['name']);
    }

    public function testMethodValidReturnsTrueOrFalseForDifferentStates()
    {
        $reader = new PDOReader();
        
        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'))
            ->setSql('SELECT * FROM demo WHERE id < :param1')
            ->setParameters(['param1' => 5]);

        $reader->open();

        $this->assertTrue($reader->valid());

        $reader->next();

        $this->assertTrue($reader->valid());

        for ($i = 1; $i <= 4; $i++) {
            $reader->next();
        }

        $this->assertFalse($reader->valid());
    }

    public function testMethodSetSqlThrowsAnExceptionForAWrongParameter()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new PDOReader();

        $reader->setSql(new \DateTime());
    }

    public function testMethodSetPdoThrowsAnExceptionForAWrongParameter()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new PDOReader();

        $reader->setPdo(new \DateTime());
    }

    public function testMethodSetPdoCreatesItsOwnInstanceByArray()
    {
        $reader = new PDOReader();

        $reader->setPdo(['sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db']);

        $this->assertInstanceOf(\PDO::class, $reader->getPdo());
    }

    public function testMethodOpenThrowsExceptionIfPdoIsNotDefined()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new PDOReader();

        $reader->open();
    }

    public function testMethodOpenThrowsExceptionIfSqlIsNotDefined()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new PDOReader();

        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'));

        $reader->open();
    }

    public function testMethodOpenReturnsFalseIfTheStatementFailed()
    {
        $reader = new PDOReader();

        $reader->setPdo(new \PDO('sqlite:' . realpath(__DIR__ . '/../../../Data') . '/sqlite.db'))
            ->setSql('SELECT * FROM demo WHERE id !!!!!');

        $this->assertFalse($reader->open());
    }

    public function testMethodGetSqlReturnsTheSql()
    {
        $reader = new PDOReader();
        $sql = 'SELECT * FROM demo';

        $reader->setSql($sql);

        $this->assertEquals($sql, $reader->getSql());
    }

    public function testMethodGetParametersReturnsTheParameters()
    {
        $reader = new PDOReader();
        $parameters = ['param1' => 'first', 'param2' => 'second'];

        $reader->setParameters($parameters);

        $this->assertEquals($parameters, $reader->getParameters());
    }
}
