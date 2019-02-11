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

use InvalidArgumentException;
use RuntimeException;
use SplFileObject;

/**
 * Pipeline SplFileObjectReader-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class SplFileObjectReader extends ReaderAbstract
{
    /**
     * SplFileObject object.
     *
     * @var null|SplFileObject
     */
    private $file;


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
        return $this->file->current();
    }

    /**
     * Returns the current defined SplFileObject object.
     *
     * @return null|SplFileObject
     */
    public function getFile(): ?SplFileObject
    {
        return $this->file;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->file->key();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->file->next();
        $this->file->current(); // important, else the pointer does not move forward
    }

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        if (!$this->file instanceof SplFileObject) {
            throw new RuntimeException('You must define a SplFileObject object first.');
        }
        
        $this->rewind();

        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->file->rewind();
        $this->file->current(); // important, else the pointer does not move forward
    }
    
    /**
     * Sets the file to work with.
     *
     * @param array|SplFileObject $file
     *
     * @throws InvalidArgumentException if the provided $file is not valid
     *
     * @return SplFileObjectReader
     */
    public function setFile($file): self
    {
        if (is_array($file)) {
            @list($filename, $openMode, $useIncludePath) = $file;
            $file = new SplFileObject($filename, $openMode ?? 'r', $useIncludePath ?? false);
        } 

        if (!$file instanceof SplFileObject) {
            throw new InvalidArgumentException('The provided File is not a valid SplFileObject instance or an array with a configuration.');
        }

        $this->file = $file;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->file->valid();
    }
}