<?php

class GameTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that the game class instantiates
     *
     * @covers \Life\Game::__construct
     */
    public function testConstructor()
    {
        $game = new \Life\Game(array());
        $this->assertInstanceOf('\Life\Game', $game);
    }

    /**
     * Test that an exception is thrown when the -f argument is provided
     * and not followed by a filename argument.
     *
     * @covers \Life\Game::__construct
     * @covers \Life\Game::parseArgs
     */
    public function testMissingFilenameException()
    {
        $this->setExpectedException('RuntimeException', 'Missing filename argument');

        $game = new \Life\Game(array('-f'));
    }
}
