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

/**
 * Pipeline ResourceReader-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class ResourceReader extends ReaderAbstract
{
    const TYPE_FOPEN = 'fopen';
    const TYPE_FSOCKOPEN = 'fsockopen';


    /**
     * Index.
     *
     * @var integer
     */
    private $index = 0;

    /**
     * @var mixed
     */
    private $record;

    /**
     * SplFileObject object.
     *
     * @var null|resource
     */
    private $resource;


    /**
     * {@inheritdoc}
     */
    public function close(): bool
    {
        if (is_resource($this->resource)) {
            return fclose($this->resource);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->record;
    }

    /**
     * Returns the current defined resource.
     *
     * @return null|resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->record = fgets($this->resource);
        $this->index++;
    }

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        if (!is_resource($this->resource)) {
            throw new RuntimeException('You must define a Resource first.');
        }
        
        $this->rewind();

        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        fseek($this->resource, 0);
        $this->record = fgets($this->resource);

        $this->index = 0;
    }
    
    /**
     * Sets the resource to work with.
     *
     * @param array|resource $resource
     *
     * @throws InvalidArgumentException if the provided $resource is not valid
     *
     * @return ResourceReader
     */
    public function setResource($resource): self
    {
        if (is_array($resource)) {
            @list($type, $param1, $param2, $param3) = $resource;

            if ($type === self::TYPE_FOPEN) {
                $resource = fopen($param1, $param2 ?? 'r', $param3 ?? false);
            }

            if ($type === self::TYPE_FSOCKOPEN) {
                $errno = null;
                $errstr = null;

                $resource = fsockopen($param1, $param2 ?? -1, $errno, $errstr, $param3 ?? ini_get('default_socket_timeout'));
            }
        } 

        if (!is_resource($resource)) {
            throw new InvalidArgumentException('The provided File is not a valid Resource or an array with a configuration.');
        }

        $this->resource = $resource;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return !feof($this->resource);
    }
}