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
            $this->getObject('test')->isConnected()
        );
    }
}
