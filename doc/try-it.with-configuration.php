<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Mordilion\Pipeline\Pipeline;

use Mordilion\Pipeline\Reader\PDOReader;
use Mordilion\Pipeline\Reader\ReaderAbstract;

use Mordilion\Pipeline\Writer\ArrayWriter;
use Mordilion\Pipeline\Writer\PDOWriter;
use Mordilion\Pipeline\Writer\WriterAbstract;

use Mordilion\Configurable\Configuration\Factory as ConfigurationFactory;


$configuration = [
    'reader' => [
        'class' => PDOReader::class,
        'configuration' => [
            'sql' => 'SELECT * FROM demo',
            'pdo' => [
                'sqlite:' . __DIR__ . '/sqlite.db',
            ],
        ],
    ],
    'writer' => [
        'class' => PDOWriter::class,
        'configuration' => [
            'sql' => 'INSERT INTO demo (' . PDOWriter::PLACEHOLDER_FIELDS . ') VALUES(' . PDOWriter::PLACEHOLDER_VALUES . ')',
            'pdo' => [
                'sqlite:' . __DIR__ . '/output.db',
            ],
        ],
    ],
];
$pipeline = new Pipeline();
$pipeline->setConfiguration($configuration);
$pipeline->transfer(function ($row, ReaderAbstract $reader, WriterAbstract $writer) {
    $row['id'] = (int)$row['id'];

    return $row;
});

/*
$json = '
{
    "reader": {
        "class": "Mordilion\\\Pipeline\\\Reader\\\PDOReader",
        "configuration": {
            "sql": "SELECT * FROM demo",
            "pdo": [
                "sqlite:' . __DIR__ . '/sqlite.db"
            ]
        }
    },
    "writer": {
        "class": "Mordilion\\\Pipeline\\\Writer\\\ArrayWriter",
    }
}
';

$pipeline = new Pipeline();
$pipeline->setConfiguration(ConfigurationFactory::create($json, 'json'));
$pipeline->transfer(function ($row) {
    $row['id'] = (int)$row['id'];

    return array_merge($row, array('created_at' => date('Y-m-d H:i:s')));
});

var_dump($pipeline->getWriter()->getData());
*/

