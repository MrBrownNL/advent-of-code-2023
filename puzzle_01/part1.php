<?php
try {
    echo "Starting..." . PHP_EOL;
    $c = new Test();
    echo "finished" . PHP_EOL;
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}

class Test {
    private $map;
    private $calibrationValues;

    public function __construct()
    {
        $inputData = file_get_contents('puzzle_input.txt');
        $this->map = explode(PHP_EOL, $inputData);
        $this->calibrationValues = Array();
        echo $this->Execute() . PHP_EOL;
    }

    public function Execute(): string {
        foreach($this->map as $line) {
            $this->calibrationValues[] = $this->getCalibrationValue($line);
        }

        return array_sum($this->calibrationValues);
    }

    public function getCalibrationValue(string $line) {
        $calibrationValue = "";

        // Get first number from line
        for ($x = 0; $x <= strlen($line) - 1; $x++) {
            $char = $line[$x];
            if (is_numeric($char)) {
                $calibrationValue .= $char;
                break;
            }
        }

        // Get last number from line
        for ($x = strlen($line) - 1; $x >= 0; $x--) {
            $char = $line[$x];
            if (is_numeric($char)) {
                $calibrationValue .= $char;
                break;
            }
        }

        return intval($calibrationValue);
    }
}
