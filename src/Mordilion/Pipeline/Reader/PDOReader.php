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
use PDO;
use RuntimeException;

/**
 * Pipeline PDOReader-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class PDOReader extends ArrayReader
{
    /**
     * Parameters for the executing of the prepared statement.
     *
     * @var array
     */
    private $parameters = array();

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
     *  Returns the current defined SQL.
     *
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
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

        $this->statement = $this->pdo->prepare($this->sql);

        if ($this->statement === false) {
            return false;
        }

        $this->statement->execute($this->parameters);
        $this->setData((array)$this->statement->fetchAll(PDO::FETCH_ASSOC)); // could take a while
        
        $this->rewind();

        return true;
    }
    
    /**
     * Sets the parameters for the prepared statement.
     *
     * @param array $parameters
     *
     * @return PDOReader
     */
    public function setParameters(array $parameters): PDOReader
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
     * @return PDOReader
     */
    public function setPdo($pdo): PDOReader
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
     * @return PDOReader
     */
    public function setSql($sql): PDOReader
    {
        if (!is_string($sql)) {
            throw new InvalidArgumentException('The provided sql has to be a string.');
        }

        $this->sql = $sql;

        return $this;
    }
}