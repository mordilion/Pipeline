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

namespace Mordilion\Pipeline\Reader;

use Iterator;
use RuntimeException;

/**
 * Pipeline IteratorReader-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class IteratorReader extends ReaderAbstract
{
    /**
     * Iterator.
     *
     * @var Iterator
     */
    private $iterator;


    /**
     * {@inheritdoc}
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->iterator->current();
    }

    /**
     * Returns the current Iterator.
     *
     * @return Iterator
     */
    public function getIterator(): ?Iterator
    {
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        if (!$this->iterator instanceof Iterator) {
            throw new RuntimeException('You must define a Iterator object first.');
        }

        $this->rewind();

        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->iterator->rewind();
    }

    /**
     * Sets the iterator to work with.
     *
     * @param Iterator $iterator
     *
     * @return IteratorReader
     */
    public function setIterator(Iterator $iterator): IteratorReader
    {
        $this->iterator = $iterator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->iterator->valid();
    }
}
