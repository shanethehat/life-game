<?php

class BoardExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test constructor accepts and stores a message
     *
     * @covers \Life\Exception\BoardException::__construct
     */
    public function testConstructorStoresMessage()
    {
        $message = 'Test message';
        $exception = new \Life\Exception\BoardException($message);
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Test constructor accepts and stores a previous exception
     *
     * @covers \Life\Exception\BoardException::__construct
     */
    public function testConstructorStoresPreviousException()
    {
        $previous = new \Exception('previous');
        $exception = new \Life\Exception\BoardException('test', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }

}

