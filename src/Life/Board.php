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

class Board
{
    protected $grid;

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
    public function createGridFromFile($filename)
    {
        try {
            $file = new \SplFileObject($filename);

            $this->grid = $this->getGridFromFile($file);

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
    public function createRandomGrid($width, $height)
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
     * Returns the grid array
     *
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
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
        // grab the first line to get an expected line length
        $line = trim($file->fgets());
        $length = strlen($line);
        try {
            // get the first line
            $grid[] = $this->getValidLine($line, $length);
            // get the remaining lines using the length of the first as a validation guide
            while ($line = $file->fgets()) {
                $grid[] = $this->getValidLine(trim($line), $length);
            }
        } catch (BoardException $exception) {
            throw new BoardException(
                sprintf('File read failed on line %d with message "%s"', $file->key() + 1, $exception->getMessage()),
                $exception
            );
        }
        return $grid;
    }

    /**
     * Returns an array of 1 and 0 characters representing a line of the grid
     *
     * @param string $line   Line of a file
     * @param int    $length Expected file length
     *
     * @return array
     */
    protected function getValidLine($line, $length)
    {
        if ($this->validateLine($line, $length)) {
            return str_split($line);
        }
        return array();
    }

    /**
     * Validates a single line of a file to ensure the length and content are correct
     *
     * @param string $line   Line of a file
     * @param int    $length Expected line length
     *
     * @throws \Life\Exception\BoardException
     * @return boolean
     */
    protected function validateLine($line, $length)
    {
        $lineLength = strlen($line);
        if ($lineLength !== $length) {
            throw new BoardException("Line length is $lineLength, $length expected");
        }
        // attempt to match the first character in the line that is not a 1 or 0
        $matches = array();
        if (preg_match('/[^10]+/', $line, $matches)) {
            throw new BoardException('Encountered unexpected character: ' . $matches[0]);
        }        
        return true;
    }


}

