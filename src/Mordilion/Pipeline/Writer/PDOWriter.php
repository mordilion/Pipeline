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
use PDO;
use RuntimeException;

/**
 * Pipeline ArrayWriter-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class PDOWriter extends WriterAbstract
{
    const PLACEHOLDER_PREFIX = '%%%';
    const PLACEHOLDER_SUFFIX = '%%%';
    const PLACEHOLDER_FIELDS = self::PLACEHOLDER_PREFIX . 'FIELDS' . self::PLACEHOLDER_SUFFIX;
    const PLACEHOLDER_VALUES = self::PLACEHOLDER_PREFIX . 'VALUES' . self::PLACEHOLDER_SUFFIX;
    const PLACEHOLDER_VALUE = self::PLACEHOLDER_PREFIX . '%s|VALUE' . self::PLACEHOLDER_SUFFIX;


    /**
     * Parameters for the executing of the prepared statement.
     *
     * @var array
     */
    private $parameters = [];

    /**
     * PDO object.
     *
     * @var null|\PDO
     */
    private $pdo;

    /**
     * SQL to query the database with the PDO object.
     *
     * @var string
     */
    private $sql = '';

    /**
     * Statement of the query.
     *
     * @var false|\PDOStatement
     */
    private $statement = false;


    /**
     * {@inheritdoc}
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Returns the current defined parameters for the prepared statement.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Returns the current defined \PDO object.
     *
     * @return null|PDO
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function open(): bool
    {
        if (!$this->pdo instanceof PDO) {
            throw new RuntimeException('You must define a PDO object first.');
        }

        if (empty($this->sql)) {
            throw new RuntimeException('You must define a SQL query first.');
        }

        return true;
    }

    /**
     * Sets the parameters for the prepared statement.
     *
     * @param array $parameters
     *
     * @return PDOWriter
     */
    public function setParameters(array $parameters): PDOWriter
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Sets the PDO to work with.
     *
     * @param array|PDO $pdo
     *
     * @throws InvalidArgumentException if the provided $pdo is not valid
     *
     * @return PDOWriter
     */
    public function setPdo($pdo): PDOWriter
    {
        if (is_array($pdo)) {
            @list($dsn, $username, $password, $options) = $pdo;
            $pdo = new PDO($dsn, (string)$username, (string)$password, (array)$options);
        }

        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException('The provided PDO is not a valid \PDO instance or an array with a configuration.');
        }

        $this->pdo = $pdo;

        return $this;
    }

    /**
     * Sets the SQL to query the database.
     *
     * @param string $sql
     *
     * @throws InvalidArgumentException if the provided $sql is not a string
     *
     * @return PDOWriter
     */
    public function setSql($sql): PDOWriter
    {
        if (!is_string($sql)) {
            throw new InvalidArgumentException('The provided sql has to be a string.');
        }

        $this->sql = $sql;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data): void
    {
        $sql = $this->sql;

        $sql = preg_replace('/' . self::PLACEHOLDER_FIELDS . '/', implode(', ', array_keys($data)), $sql);
        $sql = preg_replace('/' . self::PLACEHOLDER_VALUES . '/', '"' . implode('", "', $data) . '"', $sql);

        foreach ($data as $field => $value) {
            $sql = preg_replace('/' . sprintf(self::PLACEHOLDER_VALUE, $field) . '/', $value, $sql);
        }

        $this->statement = $this->pdo->prepare($sql);

        if ($this->statement === false) {
            return ;
        }

        $this->statement->execute($this->parameters);
    }
}
