<?php

require_once __DIR__ . '/../../vendor/autoload.php';


use Mordilion\Pipeline\Pipeline;
use Mordilion\Pipeline\Reader\ReaderAbstract;
use Mordilion\Pipeline\Writer\WriterAbstract;
use Mordilion\Pipeline\Reader\SplFileObjectReader;
use Mordilion\Pipeline\Writer\PDOWriter;


$file = new \SplFileObject(__DIR__ . '/data.csv');
$file->setFlags(\SplFileObject::READ_CSV);
$file->setCsvControl(';');

$reader = new SplFileObjectReader();
$reader->setFile($file);

$pdo = new \PDO('sqlite:' . __DIR__ . '/csv-to-database.db');
$statement = $pdo->query('CREATE TABLE demo (id INT, firstname VARCHAR(100), lastname VARCHAR(100), email VARCHAR(255))');

$writer = new PDOWriter();
$writer->setPdo($pdo)
    ->setSql('INSERT INTO demo (' . PDOWriter::PLACEHOLDER_FIELDS . ') VALUES(' . PDOWriter::PLACEHOLDER_VALUES . ')');

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer)
    ->transfer(function (array $row, ReaderAbstract $reader, WriterAbstract $writer) {
        $row = array_combine(['id', 'firstname', 'lastname', 'email'], $row);

        return $row;
    });