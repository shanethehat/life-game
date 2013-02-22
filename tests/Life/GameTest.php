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
        $game = new \Life\Game();
        $this->assertInstanceOf('\Life\Game', $game);
    }

    /**
     * Test that a board of expected size is generated when a valid width and 
     * height are supplied
     *
     * @covers \Life\Game::__construct
     * @covers \Life\Game::newGame
     */
    public function testWithWidthAndHeight()
    {
        $game = new \Life\Game(
            array(
                'width' => 5,
                'height' => 6
            )
        );

        $this->assertInstanceOf('\Life\Board', $game->getBoard());
        $this->assertEquals(5, $game->getBoard()->getWidth());
        $this->assertEquals(6, $game->getBoard()->getHeight());
    }
}
