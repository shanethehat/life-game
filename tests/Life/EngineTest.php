<?php

class EngineTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the Engine instance
     */
    public function testEngineInstance()
    {
        $engine = new \Life\Engine();
        $this->assertInstanceOf('\Life\Engine', $engine);
    }

    /**
     * Test that the engine rejects an empty board
     *
     * @covers \Life\Engine::updateGeneration
     */
    public function testEngineEmptyBoard()
    {
        $this->setExpectedException(
            '\Life\Exception\EngineException',
            'The supplied board must contain an initialized grid'
        );

        $board = new \Life\Board();
        $engine = new \Life\Engine();
        $engine->updateGeneration($board);
    }

    /**
     * Test that a live cell with less than 2 active neighbours dies
     *
     * @covers \Life\Engine::updateGeneration
     * @covers \Life\Engine::getActiveNeighbours
     * @covers \Life\Engine::isCellLive
     * @covers \Life\Engine::getLiveCellOutcome
     * @covers \Life\Engine::getDeadCellOutcome
     */
    public function testCellDiesWithFewNeighbours()
    {
        $seed = array(
            array(0, 0, 0),
            array(1, 1, 0),
            array(0, 0, 0)
        );

        $result = array(
            array(0, 0, 0),
            array(0, 0, 0),
            array(0, 0, 0)
        );

        $this->runEngineTest($seed, $result);    
    }

    /**
     * Test that a live cell with more than 3 active neighbours dies
     *
     * @covers \Life\Engine::updateGeneration
     * @covers \Life\Engine::getActiveNeighbours
     * @covers \Life\Engine::isCellLive
     * @covers \Life\Engine::getLiveCellOutcome
     * @covers \Life\Engine::getDeadCellOutcome
     */
    public function testCellDiesWithManyNeighbours()
    {
        $seed = array(
            array(1, 1, 1),
            array(1, 1, 1),
            array(1, 1, 1)
        );

        $result = array(
            array(1, 0, 1),
            array(0, 0, 0),
            array(1, 0, 1)
        );

        $this->runEngineTest($seed, $result);    
    }

    /**
     * Test that a live cell with 2 active neighbours lives. Also
     * covers dead cell with 3 neighbours coming to life.
     *
     * @covers \Life\Engine::updateGeneration
     * @covers \Life\Engine::getActiveNeighbours
     * @covers \Life\Engine::isCellLive
     * @covers \Life\Engine::getLiveCellOutcome
     * @covers \Life\Engine::getDeadCellOutcome
     */
    public function testCellLivesWithTwoNeighbours()
    {
        $seed = array(
            array(1, 0, 0),
            array(1, 1, 0),
            array(0, 0, 0)
        );

        $result = array(
            array(1, 1, 0),
            array(1, 1, 0),
            array(0, 0, 0)
        );

        $this->runEngineTest($seed, $result);    
    }

    /**
     * Test that a dead cell with 4 live neighbours does not come to life
     *
     * @covers \Life\Engine::updateGeneration
     * @covers \Life\Engine::getActiveNeighbours
     * @covers \Life\Engine::isCellLive
     * @covers \Life\Engine::getLiveCellOutcome
     * @covers \Life\Engine::getDeadCellOutcome
     */
    public function testNoResurectionWithFourNeighbours()
    {
        $seed = array(
            array(1, 0, 1),
            array(0, 0, 0),
            array(1, 0, 1)
        );

        $result = array(
            array(0, 0, 0),
            array(0, 0, 0),
            array(0, 0, 0)
        );

        $this->runEngineTest($seed, $result);
    }
    /**
     * Set up before and after boards using the input arrays, then process
     * one with the engine and check that it equals the other
     *
     * @param array $seed   Initial board state
     * @param array $result Final board state
     */
    protected function runEngineTest(array $seed, array $result)
    {
        $board = new \Life\Board();
        $board->setGrid($seed);

        $expected = new \Life\Board();
        $expected->setGrid($result);

        $engine = new \Life\Engine();
        $engine->updateGeneration($board);

        $this->assertEquals($expected, $board);
    }
}
