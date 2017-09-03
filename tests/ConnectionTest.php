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
     * @param string $connection_string
     *
     * @return Connection
     */

    protected function getObject($connection_string)
    {
        return new Connection($connection_string);
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
}
