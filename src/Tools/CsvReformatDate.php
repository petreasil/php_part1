<?php
namespace Root\App\Tools;

use Exception;
use Carbon\Carbon;

class CsvReformatDate extends CsvProcessor
{
    private string $pattern;

    public function __construct(string $pattern = 'Y-m-d')
    {
        $this->pattern = $pattern;
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $resultRows = [];
        foreach ($rows as $row) {
            $row = array_map(function ($value): mixed {
                $trimmedValue = trim($value);
                if ($this->isValidDate($trimmedValue)) {
                    $date = Carbon::parse($trimmedValue);
                    return $date->format($this->pattern);
                }
                return $value;
            }, array: $row);
            $resultRows[] = $row;
        }
        $this->writeCsv($outputFile, $resultRows);
    }

    private function isValidDate(string $value): bool
    {
        $value = trim($value);

        if ($value === '' || $value === null) {
            return false;
        }

        try {
            $parsed = Carbon::parse($value);
            return $parsed->isValid();
        } catch (Exception $e) {
            // ignore and try the next format
        }


        return false;
    }
}