[![Travis](https://img.shields.io/travis/mordilion/Pipeline.svg?branch=master)](https://travis-ci.org/mordilion/Pipeline)
[![Packagist](https://img.shields.io/packagist/dt/mordilion/pipeline.svg)](https://packagist.org/packages/mordilion/pipeline)

# Pipeline

## Description

Pipeline is a library to transfer data quick and reliable or to create exports with any kind of interfaces like \PDO, \SplFileObject(CSV, etc.), Arrays and your own implementation. It uses https://github.com/mordilion/Configurable to be full configurable. 

## Basic Example

```php
<?php

use Mordilion\Pipeline\Pipeline;
use Mordilion\Pipeline\Reader\ArrayReader;
use Mordilion\Pipeline\Writer\SplFileObjectWriter;

$data = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@domain.invalid'],
    ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane.doe@domain.invalid'],
    ['id' => 3, 'name' => 'Max Mustermann', 'email' => 'max.mustermann@domain.invalid']
];

$reader = new ArrayReader();
$reader->setData($data);

$filename = __DIR__ . '/export.csv';
$writer = new SplFileObjectWriter();
$writer->setFile(new \SplFileObject($filename, 'w'))
    ->setMode(SplFileObjectWriter::MODE_CSV);

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer)
    ->transfer();
```

## Database => CSV Example 

An example of how to export data from a SQLite database into a CSV file with additional columns.

```php
<?php

use Mordilion\Pipeline\Pipeline;
use Mordilion\Pipeline\Reader\PDOReader;
use Mordilion\Pipeline\Writer\SplFileObjectWriter;

$reader = new PDOReader();
$reader->setPdo(new \PDO('sqlite:' . __DIR__ . '/sqlite.db'))
    ->setSql('SELECT * FROM demo');

$filename = __DIR__ . '/export.csv';
$writer = new SplFileObjectWriter();
$writer->setFile(new \SplFileObject($filename, 'w'))
    ->setMode(SplFileObjectWriter::MODE_CSV);

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer)
    ->transfer(function (array $row) {
        $timestamps = [
            'created_at' => isset($row['created_at']) ? $row['created_at'] : date('Y-m-d H:i:s'),
            'updated_at' => isset($row['updated_at']) ? $row['updated_at'] : date('Y-m-d H:i:s'),
            'exported_at' => date('Y-m-d H:i:s')
        ];

        return array_merge($row, $timestamps);
    });
```