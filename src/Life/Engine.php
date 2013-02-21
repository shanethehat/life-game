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

        // if the board is dead, then it's not going to magically come back to life
        if ($board->isDead()) {
            return;
        } 


    }
}
