<?php

/**
 * Life game
 *
 * @copyright Copyright (c) 2013 Shane Auckland
 * @license   http://shaneauckland.co.uk/LICENSE.txt
 * @author    Shane Auckland <shane.auckland@gmail.com>
 */

namespace Life;

use \Life\Exception\EngineException;

/**
 * Game Engine handles the rules of Life
 */

class Engine
{
    /**
     * Updates a board with a new generation
     *
     * @param \Life\Board $board Board to update
     *
     * @return void
     */
    public function updateGeneration(Board $board)
    {
        if (0 === count($board)) {
            throw new EngineException('The supplied board must contain an initialized grid');
        }

        // if the board is dead or boring then there is no point processing a 
        // new generation
        if ($board->isDead() || $board->isBoring()) {
            return;
        }

        $grid = array();

        for ($y = 0, $height = count($board); $y < $height; $y++) {
            $grid[$y] = array();
            for ($x = 0, $width = count($board[$y]); $x < $width; $x++) {
                $activeNeighbours = $this->getActiveNeighbours($board, $x, $y);
                $grid[$y][$x] = $this->isCellLive($board, $x, $y) 
                    ? $this->getLiveCellOutcome($activeNeighbours) 
                    : $this->getDeadCellOutcome($activeNeighbours);
            }
        }
        $board->setGrid($grid);
    }

    /**
     * Returns the number of live neighbouring cells for a given grid position
     *
     * @param \Life\Board $board Board to read from
     * @param int         $x     X axis coordinate
     * @param int         $y     Y axis coordinate
     *
     * @return int
     */
    protected function getActiveNeighbours(Board $board, $x, $y)
    {
        $total = 0;
        $total += (int) $this->isCellLive($board, $x - 1, $y - 1); // top left        
        $total += (int) $this->isCellLive($board, $x, $y - 1);     // top center        
        $total += (int) $this->isCellLive($board, $x + 1, $y - 1); // top right        
        $total += (int) $this->isCellLive($board, $x - 1, $y);     // middle left        
        $total += (int) $this->isCellLive($board, $x + 1, $y);     // middle right        
        $total += (int) $this->isCellLive($board, $x - 1, $y + 1); // bottom left        
        $total += (int) $this->isCellLive($board, $x, $y + 1);     // bottom center        
        $total += (int) $this->isCellLive($board, $x + 1, $y + 1); // bottom right        
        return $total;
    }

    /**
     * Tests if a cell at the given coordinates is alive. Cells that are outside 
     * the board area are considered dead.
     *
     * @param \Life\Board $board Board to read from
     * @param int         $x     X axis coordinate
     * @param int         $y     Y axis coordinate
     *
     * @return boolean
     */
    protected function isCellLive(Board $board, $x, $y)
    {
        if ($x < 0 || $y < 0 || $x >= $board->getWidth() || $y >= $board->getHeight()) {
            return false;
        }
        return $board[$y][$x] === 1;
    }

    /**
     * Returns a 1 or 0 for the effect of the number of active neighbouring 
     * cells on a live cell
     *
     * @param int $activeNeighbours Number of active neighbours
     *
     * @return int
     */
    public function getLiveCellOutcome($activeNeighbours)
    {
        return (int) ($activeNeighbours === 2 || $activeNeighbours === 3);
    }

    /**
     * Returns a 1 or 0 for the effect of the number of active neighbouring 
     * cells on a dead cell
     *
     * @param int $activeNeighbours Number of active neighbours
     *
     * @return int
     */
    public function getDeadCellOutcome($activeNeighbours)
    {
        return (int) ($activeNeighbours === 3);
    }
   
}
