<?php

require_once __DIR__ . '/../../vendor/autoload.php';


use Mordilion\Pipeline\Pipeline;
use Mordilion\Pipeline\Reader\ExecReader;
use Mordilion\Pipeline\Writer\SplFileObjectWriter;


$reader = new ExecReader();
$reader->setCommand('ls /');

$filename = __DIR__ . '/directory-listing.csv';
$writer = new SplFileObjectWriter();
$writer->setFile(new \SplFileObject($filename, 'w'))
    ->setMode(SplFileObjectWriter::MODE_CSV);

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer)
    ->transfer();