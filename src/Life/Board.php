<?php

/**
 * Life game
 *
 * @copyright Copyright (c) 2013 Shane Auckland
 * @license   http://shaneauckland.co.uk/LICENSE.txt
 * @author    Shane Auckland <shane.auckland@gmail.com>
 */ 

namespace Life;

use Life\Exception\BoardException;

/**
 * Board class
 */

class Board implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var array
     */
    protected $grid = array();

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * Set up a new board from a text file.
     *
     * Text file must contain a grid of 1 and 0 characters. All lines must be an 
     * identical length.
     *
     * @param string $filename File name to load from
     *
     * @throws \Life\Exception\BoardException
     * @return void
     */
    public function createFromFile($filename)
    {
        try {
            $file = new \SplFileObject($filename);

            $this->setGrid($this->getGridFromFile($file));

        } catch (\Exception $exception) {
            throw new BoardException('Failed to create grid from file', $exception);
        }
    }

    /**
     * Set up a new randomly populated board from the supplied dimensions
     *
     * @param int $width  Width of the grid (3 min)
     * @param int $height Height of the grid (3 min)
     *
     * @throws \Life\Exception\BoardException
     * @return void
     */
    public function createRandom($width, $height)
    {
        if (!is_int($width) || $width < 3) {
            throw new BoardException("Width must be an integer of 3 or more, $width provided");
        }

        if (!is_int($height) || $height < 3) {
            throw new BoardException("Height must be an integer of 3 or more, $height provided");
        }

        $grid = array();

        for ($i = 0; $i < $height; $i++) {
            $grid[] = $this->createRandomGridRow($width);
        }

        $this->grid = $grid;
    }

    /**
     * Set a new multi-dimensional array as the grid
     *
     * @param array $grid New grid
     *
     * @return void
     */
    public function setGrid(array $grid)
    {
        foreach ($grid as $row) {
            if (!is_array($row)) {
                throw new BoardException('Supplied grid must only contain arrays');
            }
        }

        if (count($grid) < 3) {
            throw new BoardException('Supplied grid must be at least 3 rows high');
        }

        if (($width = count($grid[0])) < 3) {
            throw new BoardException('Supplied grid must be at least 3 columns wide');
        }
        
        for ($i = 0, $count = count($grid); $i < $count; $i++) {
            $row = $grid[$i];

            if (($rowWidth = count($row)) !== $width) {
                throw new BoardException("The width of row " . ($i + 1) . " is $rowWidth, $width expected");
            }

            $filtered = array_filter($row, function ($item) {
                return ($item !== 0 && $item !== 1);
            });

            if (count($filtered)) {
                throw new BoardException('Unexpected content in row ' . $i);
            }
        }

        $this->grid = $grid;
    }

    /**
     * Returns an array of length $length, filled with randomly chosen 1 and 0
     *
     * @param int $length Size of row
     *
     * @return array
     */
    protected function createRandomGridRow($length)
    {
        $row = array();

        for ($i = 0; $i < $length; $i++) {
            $row[] = mt_rand(0, 1);
        }

        return $row;
    }

    /**
     * Set the contents of the grid using the contents of the file
     *
     * @param \SplFileObject $file File object
     *
     * @return array
     */
    protected function getGridFromFile(\SplFileObject $file)
    {
        if ($file->eof()) {
            throw new BoardException('File is empty');
        }
        // temporary grid container
        $grid = array();
        
        while ($line = $file->fgets()) {
            $row = str_split(trim($line));
            array_walk($row, function (&$item, $index) {
                $item = ($item === '1' || $item === '0') ? (int) $item : $item;
            });
            $grid[] = $row;
        }

        return $grid;
    }


    /**
     * Returns the contents of $this->grid at $this->position
     *
     * @return array
     */
    public function current()
    {
        return $this->grid[$this->position];
    }

    /**
     * Returns the current position
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Moves the position on the the next index
     *
     * @return void
     */
    public function next()
    {
        $this->position += 1;
    }

    /**
     * Resets the position to the start
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Tests if the current position is a valid index
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->offsetExists($this->position);
    }

    /**
     * Tests if the supplied offset exists
     * 
     * @param int $offset Grid row
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->grid[$offset]);
    }

    /**
     * Return a value for a given offset
     *
     * @param int $offset Grid row
     *
     * @return array|null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->grid[$offset] : null;
    }

    /**
     * Set a value of the grid - Not permitted
     *
     * @param int   $offset Grid Row
     * @param mixed $value  New value
     *
     * @throws \Life\Exception\BoardException
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new BoardException('Single rows of the board may not be modified externally');
    }

    /**
     * Unset a grid value - not permitted
     *
     * @param int $offset Grid row
     * 
     * @throws \Life\Exception\BoardException
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new BoardException('Single rows of the board may not be modified externally');
    }

    /**
     * Return the height of the grid
     *
     * @return int
     */
    public function count()
    {
        return count($this->grid);
    }

    /**
     * Tests if every cell in the grid is dead (0)
     *
     * @return boolean
     */
    public function isDead()
    {
        foreach ($this->grid as $row) {
            if (in_array(1, $row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the width of the grid
     *
     * @return int
     */
    public function getWidth()
    {
        if (!count($this)) {
            return 0;
        }
        return count($this[0]);
    }

    /**
     * Returns the height of the grid
     *
     * @return int
     */
    public function getHeight()
    {
        return count($this);
    }

    /**
     * To string implementation
     *
     * @return string
     */
    public function __toString()
    {
        $output = '';
        foreach ($this as $row) {
            $output = implode(', ', $row) . PHP_EOL;
        }
        return $output;
    }

    /**
     * To string helper method (proxies to __toString
     *
     * @return $string
     */
    public function toString()
    {
        return $this->__toString();
    }
}

