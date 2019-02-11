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

use Mordilion\Configurable\Configurable;

/**
 * Pipeline Writer-Abstract-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
abstract class WriterAbstract implements WriterInterface
{
    /* use the following traits */
    use Configurable;

    /**
     * Writes the provided data.
     *
     * @param array $data
     *
     * @return void
     */
    abstract public function write(array $data): void;
}