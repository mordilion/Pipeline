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
 * Pipeline Writer-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface WriterInterface
{
    /**
     * Close the datasource.
     *
     * @return boolean
     */
    public function close(): bool;

    /**
     * Open the datasource.
     *
     * @return boolean
     */
    public function open(): bool;
}
