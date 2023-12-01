<?php
try {
    echo "Starting..." . PHP_EOL;
    $c = new Test();
    echo "finished" . PHP_EOL;
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}

class Test {
    private array $map;
    private array $calibrationValues;
    private bool $onlyNumericValues;
    private array $numberStrings = [
        'zero',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'eight',
        'nine',
    ];

    public function __construct()
    {
        $inputData = file_get_contents('puzzle_input.txt');
        $this->map = explode(PHP_EOL, $inputData);
        echo "Solution part 1: " . $this->Execute() . PHP_EOL;
        echo "Solution part 2: " . $this->Execute(false) . PHP_EOL;
    }

    public function Execute(bool $onlyNumericValues = true): string {
        $this->calibrationValues = [];
        $this->onlyNumericValues = $onlyNumericValues;

        foreach($this->map as $line) {
            $this->calibrationValues[] = $this->getCalibrationValue($line);
        }

        return array_sum($this->calibrationValues);
    }

    public function getCalibrationValue(string $line) {
        $calibrationValue = "";

        // Get first number
        for ($x = 0; $x <= strlen($line) - 1; $x++) {
            if ($value = $this->getValue($line, $x)) {
                $calibrationValue .= $value;
                break;
            }
        }

        // Get last number from line
        for ($x = strlen($line) - 1; $x >= 0; $x--) {
            if ($value = $this->getValue($line, $x)) {
                $calibrationValue .= $value;
                break;
            }
        }

        return intval($calibrationValue);
    }

    private function getValue(string $line, int $position): string|null
    {
        $char = $line[$position];
        if (is_numeric($char)) {
            return $char;
        }

        if (!$this->onlyNumericValues) {
            foreach($this->numberStrings as $index => $numberString) {
                if (substr($line, $position, strlen($numberString)) === $numberString) {
                    return $index;
                }
            }
        }

        return null;
    }
}
