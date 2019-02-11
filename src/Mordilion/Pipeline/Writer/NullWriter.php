<?php

/**
 * This file is part of the Pipeline package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Pipeline\Writer;

/**
 * Pipeline Null-Writer-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class NullWriter extends WriterAbstract
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
    public function open(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data): void
    {
        // nothing to do
    }
}