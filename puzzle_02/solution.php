<?php
try {
    new Test();
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}

class Test {
    private array $map;
    private array $maxCubes;

    public function __construct()
    {
        $inputData = file_get_contents('puzzle_input.txt');
        $this->map = preg_split('/\n/', $inputData, -1, PREG_SPLIT_NO_EMPTY);

        echo "Solution part 1: " . $this->calculate(12, 13, 14) . PHP_EOL;
    }

    private function calculate(int $red, int $green, int $blue): int
    {
        $sumOfGameIds = 0;

        $this->maxCubes = [
            'red' => $red,
            'green' => $green,
            'blue' => $blue,
        ];

        foreach($this->map as $line) {
            $sumOfGameIds += $this->getGameId($line) ?? 0;
        }

        return $sumOfGameIds;
    }

    private function getGameId(string $line): int|null
    {
        $parts = explode(':', $line);

        $game = explode(' ', $parts[0]);
        $gameId = intval($game[1]);

        $sets = array_map('trim', explode(';', $parts[1]));
        foreach ($sets as $set) {
            if (!$this->gamePossible($set)) {
                return null;
            }
        }

        return $gameId;
    }

    private function gamePossible(string $set): bool
    {
        $cubes = array_map('trim', explode(',', $set));

        foreach ($this->maxCubes as $color => $count) {
            foreach ($cubes as $cube) {
                $parts = explode(' ', $cube);
                if ($parts[1] === $color) {
                    if (intval($parts[0]) > $count) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
