<?php

class SquareFinder
{
    private $plateau;
    private $rows;
    private $cols;
    private $maxSize = 0;
    private $maxRow = 0;
    private $maxCol = 0;

    public function __construct($filename)
    {
        $this->plateau = $this->readPlateauFromFile($filename);
        $this->rows = count($this->plateau);
        $this->cols = strlen($this->plateau[0]);
    }

    public function findLargestSquare()
    {
        $sizes = array_fill(0, $this->rows, array_fill(0, $this->cols, 0));

        for ($row = 0; $row < $this->rows; $row++) {
            for ($col = 0; $col < $this->cols; $col++) {
                if ($this->plateau[$row][$col] === '.') {
                    $sizes[$row][$col] = $this->computeSquareSize($sizes, $row, $col);

                    if ($sizes[$row][$col] > $this->maxSize) {
                        $this->maxSize = $sizes[$row][$col];
                        $this->maxRow = $row;
                        $this->maxCol = $col;
                    }
                }
            }
        }

        return [$this->maxSize, $this->maxRow, $this->maxCol];
    }

    private function computeSquareSize($sizes, $row, $col)
    {
        if ($row === 0 || $col === 0) {
            return 1;
        } else {
            return min($sizes[$row - 1][$col], $sizes[$row][$col - 1], $sizes[$row - 1][$col - 1]) + 1;
        }
    }

    public function updatePlateauWithLargestSquare()
    {
        for ($i = 0; $i < $this->maxSize; $i++) {
            for ($j = 0; $j < $this->maxSize; $j++) {
                $this->plateau[$this->maxRow - $i][$this->maxCol - $j] = 'x';
            }
        }
    }

    public function printPlateau()
    {
        foreach ($this->plateau as $line) {
            echo $line . PHP_EOL;
        }
    }

    private function readPlateauFromFile($filename)
    {
        $plateau = [];
        $lines = file($filename);

        $numLines = (int)trim($lines[0]);

        for ($i = 1; $i <= $numLines; $i++) {
            $plateau[] = trim($lines[$i]);
        }

        return $plateau;
    }
}

if (count($argv) !== 2) {
    die("You need to enter a file in arg\n");
}

$filename = $argv[1];
$squareFinder = new SquareFinder($filename);

list($maxSize, $maxRow, $maxCol) = $squareFinder->findLargestSquare();
$squareFinder->updatePlateauWithLargestSquare();
$squareFinder->printPlateau();