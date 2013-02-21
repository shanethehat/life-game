<?php

/**
 * Life game
 *
 * @copyright Copyright (c) 2013 Shane Auckland
 * @license   http://shaneauckland.co.uk/LICENSE.txt
 * @author    Shane Auckland <shane.auckland@gmail.com>
 */ 

namespace Life;

/**
 * Main game class
 */

class Game
{
    protected $args;

    protected $board;

    /**
     * Constructor takes the command line arguments
     * 
     * @param array $args Command line arguments
     *
     * @return void
     */
    public function __construct(array $args = null)
    {
        if (is_array($args)) {
            $this->parseArgs($args);
        }
    }

    /**
     * Process an array of arguments to set up the game
     *
     * @param array $args Array of arguments
     *
     * @return void
     */
    protected function parseArgs(array $args)
    {
        if (in_array('-f', $args)) {
            $keys = array_keys($args, '-f');
            if (!isset($args[$keys[0] + 1])) {
                throw new \RuntimeException('Missing filename argument');
            }
            $this->getBoard()->createFromFile($args[$keys[0] + 1]);
        }
    }

    /**
     * Lazy load and return a board instance
     *
     * @return \Life\Board
     */
    public function getBoard()
    {
        if (is_null($this->board)) {
            $this->board = new Board();
        }
        return $this->board;
    }

    /**
     * Trigger the board to update with a new generation
     *
     * @return void
     */
    public function takeTurn()
    {
        try {
            $this->getBoard()->nextGeneration();
        } catch (Exception $exception) {
            // @todo need better exception handling
            throw $exception;
        }
    }
}

