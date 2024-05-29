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

/**
 * Pipeline ArrayReader-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class ArrayReader extends ReaderAbstract
{
    /**
     * Array with data.
     *
     * @var array
     */
    private $data = [];


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
        return current($this->data);
    }

    /**
     * Returns the current data array.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        $this->rewind();

        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->data);
    }
    
    /**
     * Sets the data to work with.
     *
     * @param array $data
     *
     * @return ArrayReader
     */
    public function setData(array $data): ArrayReader
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return key($this->data) !== null;
    }
}
