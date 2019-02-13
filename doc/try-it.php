<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Mordilion\Pipeline\Pipeline;
use Mordilion\Pipeline\Reader\NullReader;
use Mordilion\Pipeline\Reader\SplFileObjectReader;
use Mordilion\Pipeline\Reader\PDOReader;
use Mordilion\Pipeline\Writer\NullWriter;

use Mordilion\Configurable\Configuration\Factory as ConfigurationFactory;


/* BEGIN - Null */
$reader = new NullReader();
$writer = new NullWriter();

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer);

$pipeline->transfer();
/* END - Null */

/* BEGIN - CSV-File as Datasource */
$file = new \SplFileObject(__DIR__ . '/test.csv');
$file->setFlags(\SplFileObject::READ_CSV);

$reader = new SplFileObjectReader();
$reader->setFile($file);
$writer = new NullWriter();

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer);

$pipeline->transfer();
/* END - CSV-File as Datasource */

/* BEGIN - SQLite-Database as Datasource */
$pdo = new \PDO('sqlite:' . __DIR__ . '/sqlite.db');
$sql = 'SELECT * FROM demo';

$reader = new PDOReader();
$reader->setPdo($pdo, '', '', [])
    ->setSql($sql);
$writer = new NullWriter();

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer);

$pipeline->transfer();
/* END - SQLite-Database as Datasource */

/* BEGIN - SQLite-Database as Datasource with configuration */
$configuration = ConfigurationFactory::create('
{
    "sql": "SELECT * FROM demo",
    "pdo": [
        "sqlite:' . __DIR__ . '/sqlite.db"
    ]
}
', 'json');

$reader = new PDOReader();
$reader->setConfiguration($configuration);
$writer = new NullWriter();

$pipeline = new Pipeline();
$pipeline->setReader($reader)
    ->setWriter($writer);

$pipeline->transfer();
/* END - SQLite-Database as Datasource with configuration */



/* BEGIN - pure configuration */
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
        "configuration": {}
    }
}
';
$pipeline = new Pipeline();
$pipeline->setConfiguration(ConfigurationFactory::create($json, 'json'));
$pipeline->transfer(function ($row) {
    return array_merge($row, ['created_at' => date('Y-m-d H:i:s')]);
});

var_dump($pipeline->getWriter()->getData());
/* END - pure configuration */

