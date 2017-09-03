<?php

namespace ApishkaTest\DbConnection\PgSql;

use Apishka\DbConnection\PgSql\Connection;

/**
 * Query test
 */

class ConnectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Get object
     *
     * @param ... $connection_string
     *
     * @return Connection
     */

    protected function getObject(...$arguments)
    {
        return new Connection(...$arguments);
    }

    /**
     * Test not connected
     */

    public function testNotConnected()
    {
        $this->assertFalse(
            $this->getObject('dummy')->isConnected()
        );
    }

    /**
     * Test get connection string
     */

    public function testGetConnectionString()
    {
        $connection_string = 'test_connection_string';

        $this->assertEquals(
            $connection_string,
            $this->getObject($connection_string)->getConnectionString()
        );
    }

    /**
     * Test unable to connect
     *
     * @expectedException Apishka\DbConnection\PgSql\Exception
     * @expectedExceptionMessageRegExp /^Unable to connect/
     */

    public function testUnableToConnect()
    {
        $connection = $this->getObject('dummy');
        $connection->getConnection();
    }

    /**
     * Test unable to set encoding
     *
     * @expectedException Apishka\DbConnection\PgSql\Exception
     * @expectedExceptionMessageRegExp /^Unable to set encoding/
     */

    public function testUnableToSetEncoding()
    {
        $connection = $this->getObject('host=127.0.0.1 user=postgres', 'dummy');
        $connection->getConnection();
    }

    /**
     * Test unable to set encoding
     */

    public function testGetEncoding()
    {
        $connection = $this->getObject('host=127.0.0.1 user=postgres', 'UTF-8');

        $this->assertEquals(
            'UTF8',
            $connection->getEncoding()
        );
    }
}
