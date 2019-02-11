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
 * Pipeline ExecReader-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class ExecReader extends ReaderAbstract
{
    /**
     * String with command.
     *
     * @var string
     */
    private $command = 'ls /';

    /**
     * Array with output data.
     *
     * @var array
     */
    private $output = array();


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
        return current($this->output);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->output);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->output);
    }

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        exec($this->command, $this->output);

        $this->rewind();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->output);
    }

    /**
     * Sets the command to execute.
     *
     * @param string $command
     *
     * @return ExecReader
     */
    public function setCommand(string $command): ExecReader
    {
        $this->command = $command;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return key($this->output) !== null;
    }
}