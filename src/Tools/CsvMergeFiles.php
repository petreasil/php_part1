<?php
namespace Root\App\Tools;
use Exception;

class CsvMergeFiles extends CsvProcessor
{
    public function mergeCsv(string $inputFile1, string $inputFile2, string $outputFile)
    {
        if (!file_exists($inputFile1)) {
            throw new Exception("File not found: $inputFile1");
        }
        if (!file_exists($inputFile2)) {
            throw new Exception("File not found: $inputFile2");
        }
        $rows1 = $this->readCsv($inputFile1);
        $rows2 = $this->readCsv($inputFile2);
        $header1 = $rows1[0] ?? [];
        $data1 = array_slice($rows1, 1);

        $header2 = $rows2[0] ?? [];
        $data2 = array_slice($rows2, 1);

        // Merge headers
        $mergedHeader = array_merge($header1, $header2);

        // Get the max row count between both files
        $maxRows = max(count($data1), count($data2));
        $mergedRows = [$mergedHeader];

        for ($i = 0; $i < $maxRows; $i++) {
            $row1 = $data1[$i] ?? array_fill(0, count($header1), '');
            $row2 = $data2[$i] ?? array_fill(0, count($header2), '');
            $mergedRows[] = array_merge($row1, $row2);
        }
        $this->writeCsv($outputFile, $mergedRows);
    }
    public function process(string $inputFile1, string $outputFile): void
    {
        throw new Exception("Use mergeCsv() instead of process()");
    }
}