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

use Mordilion\Configurable\Configurable;

/**
 * Pipeline ReaderAbstract-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
abstract class ReaderAbstract implements ReaderInterface
{
    /* use the following traits */
    use Configurable;


    /**
     * {@inheritdoc}
     */
    abstract public function current();

    /**
     * {@inheritdoc}
     */
    abstract public function key();

    /**
     * {@inheritdoc}
     */
    abstract public function next();
    
    /**
     * {@inheritdoc}
     */
    abstract public function rewind();

    /**
     * {@inheritdoc}
     */
    abstract public function valid(): bool;
}