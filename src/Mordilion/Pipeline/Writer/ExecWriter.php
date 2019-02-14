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
class ExecWriter extends WriterAbstract
{
    /**
     * Callback for the output of an exec command.
     *
     * @var callable|null
     */
    private $callback;


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
     * @param callable|null $callback
     *
     * @return ExecWriter
     */
    public function setOutputCallback(?callable $callback): ExecWriter
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data): void
    {
        exec((string)reset($data), $output);

        if (is_array($output) && is_callable($this->callback)) {
            call_user_func_array($this->callback, array($output));
        }
    }
}