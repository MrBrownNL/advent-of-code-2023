<?php
try {
    new Test();
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}

class Test {
    private array $map;
    private array $maxCubes;
    private array $currentSet;

    public function __construct()
    {
        $inputData = file_get_contents('puzzle_input.txt');
        $this->map = preg_split('/\n/', $inputData, -1, PREG_SPLIT_NO_EMPTY);

        echo "Solution part 1: " . $this->calculatePart1(12, 13, 14) . PHP_EOL;
        echo "Solution part 2: " . $this->calculatePart2() . PHP_EOL;
    }

    private function calculatePart1(int $red, int $green, int $blue): int
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

    private function calculatePart2(): int
    {
        $sumOfPowerOfSets = 0;
        foreach($this->map as $line) {
            $sumOfPowerOfSets += $this->getPowerOfSets($line);
        }

        return $sumOfPowerOfSets;
    }

    private function getPowerOfSets(string $line): int
    {
        $this->currentSet = [
            'red' => 0,
            'green' => 0,
            'blue' => 0,
        ];

        $parts = explode(':', $line);
        $sets = array_map('trim', explode(';', $parts[1]));

        foreach ($sets as $set) {
            $this->setMaxSetValues($set);
        }

        $powerOfSets = 0;
        array_map(function($value) use (&$powerOfSets) {
            $powerOfSets = $powerOfSets === 0 ? $value : $powerOfSets * $value;
        }, $this->currentSet);

        return $powerOfSets;
    }

    private function setMaxSetValues(string $set): void
    {
        $cubes = array_map('trim', explode(',', $set));

        foreach ($cubes as $cube) {
            $parts = explode(' ', $cube);
            $cubeCount = intval($parts[0]);
            if ($cubeCount > $this->currentSet[$parts[1]]) {
                $this->currentSet[$parts[1]] = $cubeCount;
            }
        }
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
