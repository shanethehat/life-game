<?php

class BoardTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test instance
     */
    public function testBoardInstance()
    {
        $board = new \Life\Board();
        $this->assertInstanceOf('\Life\Board', $board);
    }

    /**
     * Test that the grid starts empty
     *
     * @covers \Life\Board::getGrid
     */
    public function testGridStartsEmpty()
    {
        $board = new \Life\Board();
        $this->assertNull($board->getGrid());
    }

    /**
     * Test that the expected exception is thrown when trying to create a grid 
     * from a file that doesn't exist.
     *
     * @covers \Life\Board::createGridFromFile
     */
    public function testCreateWithMissingFileException()
    {
        $board = new \Life\Board();

        $this->setExpectedException(
            '\Life\Exception\BoardException',
            'Failed to create grid from file'
        );

        $board->createGridFromFile('thisfiledoesnotexist.txt');
    }

    /**
     * Test the expected grid is generated from a valid sample file
     *
     * @covers \Life\Board::createGridFromFile
     * @covers \Life\Board::getGridFromFile
     * @covers \Life\Board::getValidLine
     * @covers \Life\Board::validateLine
     */
    public function testGridFromValidFile()
    {
        $board = new \Life\Board();

        $expected = array( 
            array('0', '0', '1', '0', '0', '0', '0', '0'),            
            array('0', '0', '1', '1', '0', '0', '1', '0'),
            array('0', '0', '1', '0', '0', '1', '0', '0'),
            array('0', '1', '1', '0', '0', '1', '1', '0'),
            array('0', '1', '1', '1', '1', '0', '1', '1'),
            array('0', '0', '1', '1', '0', '0', '1', '1'),
            array('0', '0', '1', '0', '1', '0', '1', '0'),
            array('0', '0', '0', '1', '1', '0', '0', '1')
        );
        
        $board->createGridFromFile('boards/valid.txt');
        $this->assertEquals($expected, $board->getGrid());
    }

    /**
     * Test that grid generation fails when the line lengths in the supplied 
     * file do not match
     *
     * @covers \Life\Board::createGridFromFile
     * @covers \Life\Board::getGridFromFile
     * @covers \Life\Board::getValidLine
     * @covers \Life\Board::validateLine
     */
    public function testGridCreateFailsWithShortLineLength()
    {
        $board = new \Life\Board();

        try {
            $board->createGridFromFile('boards/short-length.txt');
        } catch (\Life\Exception\BoardException $exception) {
            $this->assertEquals('Failed to create grid from file', $exception->getMessage());
            $previous = $exception->getPrevious();
            $this->assertInstanceOf('\Life\Exception\BoardException', $previous);
            $this->assertEquals(
                'File read failed on line 3 with message "Line length is 7, 8 expected"',
                $previous->getMessage()
            );
            return;
        }

        $this->fail('Expected exception was not thrown');
    }


    /**
     * Test that grid generation fails when the line lengths in the supplied 
     * file do not match
     *
     * @covers \Life\Board::createGridFromFile
     * @covers \Life\Board::getGridFromFile
     * @covers \Life\Board::getValidLine
     * @covers \Life\Board::validateLine
     */
    public function testGridCreateFailsWithLongLineLength()
    {
        $board = new \Life\Board();

        try {
            $board->createGridFromFile('boards/long-length.txt');
        } catch (\Life\Exception\BoardException $exception) {
            $this->assertEquals('Failed to create grid from file', $exception->getMessage());
            $previous = $exception->getPrevious();
            $this->assertInstanceOf('\Life\Exception\BoardException', $previous);
            $this->assertEquals(
                'File read failed on line 6 with message "Line length is 9, 8 expected"',
                $previous->getMessage()
            );
            return;
        }

        $this->fail('Expected exception was not thrown');
    }


    /**
     * Test that grid generation fails when an unexpected character is found
     *
     * @covers \Life\Board::createGridFromFile
     * @covers \Life\Board::getGridFromFile
     * @covers \Life\Board::getValidLine
     * @covers \Life\Board::validateLine
     */
    public function testGridCreateFailsWithUnexpectedCharacter()
    {
        $board = new \Life\Board();

        try {
            $board->createGridFromFile('boards/bad-character.txt');
        } catch (\Life\Exception\BoardException $exception) {
            $this->assertEquals('Failed to create grid from file', $exception->getMessage());
            $previous = $exception->getPrevious();
            $this->assertInstanceOf('\Life\Exception\BoardException', $previous);
            $this->assertEquals(
                'File read failed on line 4 with message "Encountered unexpected character: a"',
                $previous->getMessage()
            );
            return;
        }

        $this->fail('Expected exception was not thrown');
    }
}
