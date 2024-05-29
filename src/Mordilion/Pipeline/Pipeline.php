<?php

declare(strict_types=1);

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
use Mordilion\Pipeline\Reader\ReaderInterface;
use Mordilion\Pipeline\Writer\WriterAbstract;
use Mordilion\Pipeline\Reader\ReaderAbstract;
use Mordilion\Pipeline\Writer\WriterInterface;
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
     * @throws InvalidArgumentException if no reader or writer was defined
     * @throws RuntimeException if the reader or writer cannot be opened or closed
     *
     * @return void
     */
    public function transfer(?callable $callback = null): void
    {
        $this->validateMembers();
        $this->openResources();

        foreach ($this->reader as $row) {
            if (is_callable($callback)) {
                $row = call_user_func($callback, $row, $this->reader, $this->writer);
            }

            $this->writer->write((array)$row);
        }

        $this->closeResources();
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
            $reader = $this->analyzeReaderConfiguration($reader);
        }

        if (!$reader instanceof ReaderInterface) {
            throw new InvalidArgumentException('The provided reader must be an instance of ReaderInterface or a configuration array.');
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
            $writer = $this->analyzeWriterConfiguration($writer);
        }

        if (!$writer instanceof WriterInterface) {
            throw new InvalidArgumentException('The provided writer must be an instance of WriterInterface or a configuration array.');
        }

        $this->writer = $writer;

        return $this;
    }

    /**
     * Returns a reader object based on the provided configuration array.
     *
     * @param array $configuration
     *
     * @return ReaderInterface
     */
    private function analyzeReaderConfiguration(array $configuration): ReaderInterface
    {
        if (!isset($configuration['class'])) {
            throw new RuntimeException('Wrong configuration format.');
        }

        $class = $configuration['class'];
        $object = new $class();

        if (!$object instanceof ReaderInterface) {
            throw new RuntimeException('It is not an allowed class.');
        }

        if ($object instanceof ReaderAbstract && !empty($configuration['configuration'] ?? null)) {
            $object->setConfiguration($configuration['configuration']);
        }

        return $object;
    }

    /**
     * Returns a reader object based on the provided configuration array.
     *
     * @param array $configuration
     *
     * @return WriterInterface
     */
    private function analyzeWriterConfiguration(array $configuration): WriterInterface
    {
        if (!isset($configuration['class'])) {
            throw new RuntimeException('Wrong configuration format.');
        }

        $class = $configuration['class'];
        $object = new $class();

        if (!$object instanceof WriterInterface) {
            throw new RuntimeException('It is not an allowed class.');
        }

        if ($object instanceof WriterAbstract && !empty($configuration['configuration'] ?? null)) {
            $object->setConfiguration($configuration['configuration']);
        }

        return $object;
    }

    private function validateMembers(): void
    {
        if (!$this->reader instanceof ReaderAbstract) {
            throw new InvalidArgumentException('You have to define a valid reader to run the transfer process.');
        }

        if (!$this->writer instanceof WriterAbstract) {
            throw new InvalidArgumentException('You have to define a valid writer to run the transfer process.');
        }
    }

    private function openResources(): void
    {
        if (!$this->writer->open()) {
            throw new RuntimeException('Could not open writer.');
        }

        if (!$this->reader->open()) {
            throw new RuntimeException('Could not open reader.');
        }
    }

    private function closeResources(): void
    {
        if (!$this->reader->close()) {
            throw new RuntimeException('Could not close reader.');
        }

        if (!$this->writer->close()) {
            throw new RuntimeException('Could not close writer.');
        }
    }
}
