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

use InvalidArgumentException;
use RuntimeException;
use SplFileObject;

/**
 * Pipeline SplFileObject-Writer-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class SplFileObjectWriter extends WriterAbstract
{
    const MODE_NORMAL = 'normal';
    const MODE_CSV    = 'csv';


    /**
     * All available modes.
     *
     * @var array
     */
    public static $availableModes = [
        self::MODE_NORMAL,
        self::MODE_CSV
    ];

    /**
     * SplFileObject object.
     *
     * @var null|\SplFileObject
     */
    private $file;

    /**
     * Mode of writing data.
     *
     * @var string
     */
    private $mode = self::MODE_NORMAL;


    /**
     * {@inheritdoc}
     */
    public function close(): bool
    {
        return $this->file->fflush();
    }

    /**
     * Returns the current defined \SplFileObject object.
     *
     * @return null|\SplFileObject
     */
    public function getFile(): ?SplFileObject
    {
        return $this->file;
    }

    /**
     * Returns the current writing mode.
     *
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        if (!$this->file instanceof SplFileObject) {
            throw new RuntimeException('You must define a SplFileObject object first.');
        }

        return true;
    }

    /**
     * Sets the file to work with.
     *
     * @param array|SplFileObject $file
     *
     * @throws InvalidArgumentException if the provided $file is not valid
     *
     * @return SplFileObjectWriter
     */
    public function setFile($file): self
    {
        if (is_array($file)) {
            @list($filename, $openMode, $useIncludePath) = $file;
            $file = new SplFileObject($filename, !empty($openMode) ? $openMode : 'w', !empty($useIncludePath) ? $useIncludePath : false);
        } 

        if (!$file instanceof SplFileObject) {
            throw new InvalidArgumentException('The provided File is not a valid SplFileObject instance or an array with a configuration.');
        }

        $this->file = $file;

        return $this;
    }

    /**
     * Sets the mode of writing data.
     *
     * @param string $mode
     *
     * @throws InvalidArgumentException if the provided $mode is unknown
     *
     * @return SplFileObjectWriter
     */
    public function setMode(string $mode): self
    {
        $mode = strtolower($mode);

        if (!in_array($mode, self::$availableModes)) {
            throw new InvalidArgumentException('Unknown mode "' . $mode . '".');
        }

        $this->mode = $mode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data): void
    {
        $csvControl = $this->file->getCsvControl();

        if ($this->mode == self::MODE_NORMAL) {
            $this->file->fwrite(implode($csvControl[0], $data));
            return ;
        } 

        if ($this->mode == self::MODE_CSV) {
            $this->file->fputcsv($data, $csvControl[0], $csvControl[1], $csvControl[2]);
            return ;
        }
    }
}
