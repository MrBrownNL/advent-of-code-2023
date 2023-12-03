<?php
try {
    new Test();
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}

class Test
{
    private array $map;
    private $currentX;
    private $currentY;
    private $currentLine;

    public function __construct()
    {
        $inputData = file_get_contents('puzzle_input.txt');
        $this->map = array_map(function ($line) {
            return str_split($line);
        }, preg_split('/\n/', $inputData, -1, PREG_SPLIT_NO_EMPTY));

        echo "Solution part 1: " . $this->calculatePart1() . PHP_EOL;
//        echo "Solution part 2: " . $this->calculatePart2() . PHP_EOL;
    }

    private function calculatePart1(): int
    {
        $sumOfPartNumbers = 0;

        foreach ($this->map as $index => $line) {
            $this->currentY = $index;
            $this->currentX = 0;
            $this->currentLine = $line;

            $sumOfPartNumbers += $this->getSumOfValidPartNumbersFromLine() ?? 0;
        }

        return $sumOfPartNumbers;
    }

    private function calculatePart2(): int
    {
        return 0;
    }

    private function getSumOfValidPartNumbersFromLine(): int
    {
        $validPartNumbers = [];

        while ($this->currentX < count($this->currentLine) - 1) {
            if (is_numeric($this->currentLine[$this->currentX])) {
                $number = $this->getPartNumberFromCurrentPosition();

                if ($this->validatePartNumber($number)) {
                    $validPartNumbers[] = intval($number);
                }
            }

            $this->currentX++;
        }

        return array_sum($validPartNumbers);
    }

    private function getPartNumberFromCurrentPosition(): string
    {
        $number = '';

        while ($this->currentX < count($this->currentLine) && is_numeric($this->currentLine[$this->currentX])) {
            $number .= $this->currentLine[$this->currentX];

            $this->currentX++;
        }

        return $number;
    }

    private function validatePartNumber(string $number): bool
    {
        $startPositionX = max($this->currentX - strlen($number) - 1, 0);
        $endPositionX = min($this->currentX, count($this->currentLine) - 1);
        $startPositionY = max($this->currentY - 1, 0);
        $endPositionY = min($this->currentY + 1, count($this->map) - 1);

        for ($x = $startPositionX; $x <= $endPositionX; $x++) {
            for ($y = $startPositionY; $y <= $endPositionY; $y++) {

                if ($y === $this->currentY && $x > $startPositionX && $x < $endPositionX) {
                    // We don't have to check the number itself
                    continue;
                }

                if ($this->isSymbol($this->map[$y][$x])) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isSymbol(string $symbol): bool
    {
        preg_match_all("/[^0-9.]/", $symbol, $matches, PREG_OFFSET_CAPTURE);
        return count($matches[0]) > 0;
    }
}
