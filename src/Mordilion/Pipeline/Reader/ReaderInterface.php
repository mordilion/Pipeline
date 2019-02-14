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
 * Pipeline Reader-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface ReaderInterface extends \Iterator
{
    /**
     * Close the datasource.
     *
     * @return bool
     */
    public function close(): bool;

    /**
     * Open the datasource.
     *
     *
     * @return bool
     */
    public function open(): bool;
}