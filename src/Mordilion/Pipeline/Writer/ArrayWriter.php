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

namespace Mordilion\Pipeline\Writer;

/**
 * Pipeline ArrayWriter-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class ArrayWriter extends WriterAbstract
{
    /**
     * Data array.
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
    public function open(): bool
    {
        unset($this->data);

        $this->data = [];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data): void
    {
        $this->data[] = $data;
    }
}
