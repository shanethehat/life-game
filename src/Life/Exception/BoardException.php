<?php

/**
 * Life game
 *
 * @copyright Copyright (c) 2013 Shane Auckland
 * @license   http://shaneauckland.co.uk/LICENSE.txt
 * @author    Shane Auckland <shane.auckland@gmail.com>
 */ 

namespace Life\Exception;

/**
 * Board exception
 */

class BoardException extends \Exception
{
    /**
     * Customer constructor removes the need to specify a code argument
     *
     * @param string $message Exception message
     * @param \Exception $previous Previos exception for exception chaining
     *
     * @return void
     */
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
