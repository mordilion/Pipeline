<?php

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
 * Pipeline NullReader-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class NullReader extends ReaderAbstract
{
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
    {}

    /**
     * {@inheritdoc}
     */
    public function key()
    {}

    /**
     * {@inheritdoc}
     */
    public function next()
    {}

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rewind()
    {}

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return false;
    }
}