<?php

namespace Apishka\DbConnection\PgSql;

use Apishka\DbConnection\StdLib\ConnectionAbstract;
use Apishka\DbQuery\StdLib\QueryAbstract;

/**
 * Connection
 */

class Connection extends ConnectionAbstract
{
    /**
     * Connection
     *
     * @var Resource
     */

    private $_connection = null;

    /**
     * Connection string
     *
     * @var string
     */

    private $_connection_string = null;

    /**
     * Connection encoding
     *
     * @var string
     */

    private $_connection_encoding = null;

    /**
     * Construct
     *
     * @param string $connection_string
     * @param string $connection_encoding
     */

    public function __construct($connection_string, $connection_encoding = null)
    {
        $this->_connection_string = $connection_string;
        $this->_connection_encoding = $connection_encoding;
    }

    /**
     * Get connection string
     *
     * @return string
     */

    public function getConnectionString()
    {
        return $this->_connection_string;
    }

    /**
     * Get connection encoding
     *
     * @return string
     */

    public function getConnectionEncoding()
    {
        return pg_client_encoding($this->getConnection());
    }

    /**
     * Get connection
     *
     * @return Resource
     */

    public function getConnection()
    {
        if ($this->isConnected())
            return $this->_connection;

        $this->_connection = @pg_connect($this->_connection_string);

        if ($this->_connection === false)
            throw new Exception('Unable to connect: ' . var_export($this->_connection_string, true));

        if ($this->_connection_encoding && pg_set_client_encoding($this->_connection, $this->_connection_encoding) !== 0)
            throw new Exception('Unable to set encoding ' . var_export($this->_connection_encoding, true));

        return $this->_connection;
    }

    /**
     * Execute
     *
     * @param QueryAbstract $query
     *
     * @return ConnectionResult
     */

    public function execute(QueryAbstract $query)
    {
        return $this->executeString($query->__toString());
    }

    /**
     * Execute string
     *
     * @param string $query
     *
     * @return ConnectionResult
     */

    public function executeString($query)
    {
        return $this->query($query);
    }

    /**
     * Query
     *
     * @param string $query
     *
     * @return ConnectionResult
     */

    protected function query($query)
    {
        if (!pg_send_query($this->getConnection(), $query))
            throw new ConnectionException('Unable to send query');

        $result = pg_get_result($this->getConnection());
        if ($result === false)
            throw new ConnectionException('Unknown error while executing query: ' . var_export($query, true));

        $status = pg_result_status($result);
        // check status

        return $result;
    }

    /**
     * Is connected
     *
     * @return bool
     */

    public function isConnected()
    {
        return is_resource($this->_connection);
    }

    /**
     * Escape
     *
     * @param string $string
     *
     * @return string
     */

    public function escape($string)
    {
        return pg_escape_string($this->getConnection(), $string);
    }

    /**
     * Escape bytea
     *
     * @param string $string
     *
     * @return string
     */

    public function escapeBytea($string)
    {
        return pg_escape_bytea($this->getConnection(), $string);
    }

    /**
     * Destruct
     */

    public function __destruct()
    {
        if ($this->isConnected())
            pg_close($this->_connection);

        $this->_connection = null;
    }
}
