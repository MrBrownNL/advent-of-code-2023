<?php
try {
    new Test();
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}

class Test
{
    private array $lines;

    public function __construct()
    {
        $inputData = file_get_contents('puzzle_input.txt');
        $map = preg_split('/\n/', $inputData, -1, PREG_SPLIT_NO_EMPTY);
        $this->lines = array_column(array_map(fn ($line) => explode(":", $line), $map),1);

        echo "Solution part 1: " . $this->calculatePart1() . PHP_EOL;
        echo "Solution part 2: " . $this->calculatePart2() . PHP_EOL;
    }

    private function calculatePart1(): int
    {
        $sumOfWinningCards = 0;

        foreach ($this->lines as $line) {
            $match = $this->getMatchingNumbers($line);

            $sumOfWinningCards += count($match) < 3 ? count($match) : pow(2, count($match) - 1);
        }

        return $sumOfWinningCards;
    }

    private function calculatePart2(): int
    {
        $scratchCards = array_fill(0, count($this->lines), 1);

        foreach ($this->lines as $index => $line) {
            $match = $this->getMatchingNumbers($line);

            for ($i = $index + 1; $i <= $index + count($match); $i++) {
                if ($i < count($scratchCards)) {
                    $scratchCards[$i] += $scratchCards[$index];
                }
            }
        }

        return array_sum($scratchCards);
    }

    private function getMatchingNumbers(string $line): array
    {
        $game = explode("|", $line);
        $winningNumbers = array_filter(explode(" ", $game[0]), fn($value) => $value > 0);
        $cardNumbers = array_filter(explode(" ", $game[1]), fn($value) => $value > 0);

        return array_intersect($winningNumbers, $cardNumbers);
    }
}
