<?php
namespace Root\App\Tools;
use Exception;

class tool1
{
    private string $inputFile;
    private string $outputFile;
    private array $headerRow;

    public function __construct(string  $inputFile, string  $outputFile, array $headerRow)
    {
        $this->inputFile = $inputFile;
        $this->outputFile = $outputFile;
        $this->headerRow = $headerRow;
    }

    public function prependHeader(): void
    {
        $inputHandle = fopen($this->inputFile, 'r');
        if (!$inputHandle) {
            throw new Exception('Failed to open input file');
        }

        $outputHandle = fopen($this->outputFile, 'w');
        if (!$outputHandle) {
            throw new Exception('Failed to open output file');
        }

        // Write the header row to the output file
        fputcsv($outputHandle, $this->headerRow, ',', '"', '\\');

        // Read the input file and write to the output file
        while (($data = fgetcsv($inputHandle, 0, ',', '"', '\\')) !== FALSE) {
            fputcsv($outputHandle, $data, ',', '"', '\\');
        }

        fclose($inputHandle);
        fclose($outputHandle);
    }

    public function addIndexColumn(
        string $indexColumnName = 'ID',
        int $startIndex = 1,
        int $increment = 1,
        string $position = 'start'
    ): bool {
        try {
            if (!file_exists($this->inputFile)) {
                throw new Exception("Input file does not exist: {$this->inputFile}");
            }

            $inputHandle = fopen($this->inputFile, 'r');
            $outputHandle = fopen($this->outputFile, 'w');

            if (!$inputHandle || !$outputHandle) {
                throw new Exception("Unable to open file handles");
            }

            $currentIndex = $startIndex;
            $isFirstRow = true;

            while (($row = fgetcsv($inputHandle, 0, ',', '"', '\\')) !== false) {
                if ($isFirstRow ) {
                    // Add index column header
                    $newRow = $position === 'start'
                        ? array_merge([$indexColumnName], $row)
                        : array_merge($row, [$indexColumnName]);
                } else {
                    // Add index value
                    $indexValue =  $isFirstRow ? $indexColumnName : $currentIndex;
                    $newRow = $position === 'start'
                        ? array_merge([$indexValue], $row)
                        : array_merge($row, [$indexValue]);

                    if (!$isFirstRow) {
                        $currentIndex += $increment;
                    }
                }

                fputcsv($outputHandle, $newRow, 0, ',', '"', '\\');
                $isFirstRow = false;
            }

            fclose($inputHandle);
            fclose($outputHandle);

            return true;

        } catch (Exception $e) {
            error_log("CSV Index Manager Error: " . $e->getMessage());
            return false;
        }
    }
}