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
    protected $board;

    /**
     * Constructor accepts an $options array for quickstart
     * 
     * @param array $options Config options
     *
     * @return void
     */
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->newGame($options);
        }
    }

    /**
     * Start a new session, either with grid dimensions or a seed file,
     * as defined in the passed config object.
     *
     * @param array $options Options array
     *
     * @return \Life\Game
     */
    public function newGame(array $options)
    {
        // test for a seed file first
        if (in_array('file', $options)) {
            $this->getBoard()->createFromFile($options['file']);
            return $this;
        }
        // check for a manually defined array
        if (in_array('grid', $options)) {
            $this->getBoard()->setGrid($options('grid'));
            return $this;
        }
        // check for dimensions
        if (in_array('width', $options) && in_array('height', $options)) {
            $this->getBoard()->createRandom($options['width'], $options['height']);
            return $this;
        }
        // required options not satisfied, fail
        throw new \BadMethodCallException('Supplied options must define either a file or dimensions');
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
     * @return \Life\Game
     */
    public function takeTurn()
    {
        try {
            $this->getBoard()->nextGeneration();
        } catch (Exception $exception) {
            // @todo need better exception handling
            throw $exception;
        }
        return $this;
    }
}

