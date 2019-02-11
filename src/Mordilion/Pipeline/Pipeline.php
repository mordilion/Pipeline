<?php

/**
 * This file is part of the Pipeline package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Pipeline;

use InvalidArgumentException;
use Mordilion\Configurable\Configurable;
use Mordilion\Pipeline\Writer\WriterAbstract;
use Mordilion\Pipeline\Reader\ReaderAbstract;
use RuntimeException;

/**
 * Pipeline Pipeline-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Pipeline
{
    /* use the following traits */
    use Configurable;


    /**
     * Writer to handle the data for the destination.
     *
     * @var WriterAbstract
     */
    private $writer;

    /**
     * Reader to handle the data for the source.
     *
     * @var ReaderAbstract
     */
    private $reader;


    /**
     * Starts the transfer process with the defined reader and writer. 
     *
     * The callback method will be called once for each data row.
     *
     * @param callable|null $callback
     *
     * @throws InvalidArgumentException if no reader was defined
     * @throws InvalidArgumentException if no writer was defined
     * @throws RuntimeException if the writer cannot opened
     * @throws RuntimeException if the reader cannot opened
     * @throws RuntimeException if the reader cannot closed
     * @throws RuntimeException if the writer cannot closed
     *
     * @return void
     */
    public function transfer(?callable $callback = null): void
    {
        if (!$this->reader instanceof ReaderAbstract) {
            throw new InvalidArgumentException('You have to define a valid reader to run the transfer process.');
        }

        if (!$this->writer instanceof WriterAbstract) {
            throw new InvalidArgumentException('You have to define a valid writer to run the transfer process.');
        }

        if (!$this->writer->open()) {
            throw new RuntimeException('Could not open writer.');
        }

        if (!$this->reader->open()) {
            throw new RuntimeException('Could not open reader.');
        }

        foreach ($this->reader as $row) {
            if (is_callable($callback)) {
                $row = call_user_func($callback, $row, $this->reader, $this->writer);
            }

            $this->writer->write($row);
        }

        if (!$this->reader->close()) {
            throw new RuntimeException('Could not close reader.');
        }

        if (!$this->writer->close()) {
            throw new RuntimeException('Could not close writer.');
        }
    }

    /**
     * Returns the current reader object.
     *
     * @return ReaderAbstract|null
     */
    public function getReader(): ?ReaderAbstract
    {
        return $this->reader;
    }

    /**
     * Returns the current writer object.
     *
     * @return WriterAbstract|null
     */
    public function getWriter(): ?WriterAbstract
    {
        return $this->writer;
    }

    /**
     * Sets the reader for the transfer process.
     *
     * @param array|ReaderAbstract $reader
     *
     * @return Pipeline
     */
    public function setReader($reader): Pipeline
    {
        if (is_array($reader)) {
            $reader = $this->analyzeConfiguration($reader);
        }

        if (!$reader instanceof ReaderAbstract) {
            throw new InvalidArgumentException('The provided reader must be an instance of ReaderAbstract or a configuration array.');
        }

        $this->reader = $reader;

        return $this;
    }

    /**
     * Sets the writer for the transfer process.
     *
     * @param array|WriterAbstract $writer
     *
     * @return Pipeline
     */
    public function setWriter($writer): Pipeline
    {
        if (is_array($writer)) {
            $writer = $this->analyzeConfiguration($writer);
        }

        if (!$writer instanceof WriterAbstract) {
            throw new InvalidArgumentException('The provided writer must be an instance of WriterAbstract or a configuration array.');
        }

        $this->writer = $writer;

        return $this;
    }

    /**
     * Returns a reader or writer object based on the provided configuration array.
     *
     * @param array $configuration
     *
     * @return ReaderAbstract|WriterAbstract
     */
    private function analyzeConfiguration(array $configuration): Object
    {
        if (!isset($configuration['class'])) {
            throw new RuntimeException('Wrong configuration format.');
        }

        $class = $configuration['class'];
        $object = new $class();

        if (!$object instanceof ReaderAbstract && !$object instanceof WriterAbstract) {
            throw new RuntimeException('It is not an allowed class.');
        }

        if (!empty($configuration['configuration'] ?? null)) {
            $object->setConfiguration($configuration['configuration']);
        }

        return $object;
    }
}