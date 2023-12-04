<?php
try {
    new Test();
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}

class Test
{
    private array $map;

    public function __construct()
    {
        $inputData = file_get_contents('puzzle_input.txt');
        $this->map = preg_split('/\n/', $inputData, -1, PREG_SPLIT_NO_EMPTY);

        echo "Solution part 1: " . $this->calculatePart1() . PHP_EOL;
//        echo "Solution part 2: " . $this->calculatePart2() . PHP_EOL;
    }

    private function calculatePart1(): int
    {
        $sumOfWinningCards = 0;

        $lines = array_column(array_map(fn ($line) => explode(":", $line), $this->map),1);

        foreach ($lines as $line) {
            $game = explode("|", $line);
            $winningNumbers = array_filter(explode(" ", $game[0]), fn($value) => $value > 0);
            $cardNumbers = array_filter(explode(" ", $game[1]), fn($value) => $value > 0);

            $match = array_intersect($winningNumbers, $cardNumbers);

            $sumOfWinningCards += count($match) < 3 ? count($match) : pow(2, count($match) - 1);
        }

        return $sumOfWinningCards;
    }

    private function calculatePart2(): int
    {
       return 0;
    }
}
