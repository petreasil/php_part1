<?php

namespace Root\App\Tools;

class CsvReorderColumn extends CsvProcessor
{
    private $newColumnOrder;

    public function __construct(array $newColumnOrder = [])
    {
        $this->newColumnOrder = $newColumnOrder;
    }

    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $originalHeader = $rows[0];
        $headerIndexMap = array_flip($originalHeader);

        $reorderedRows = [];
        foreach ($rows as $row) {
            $reorderedRow = [];
            foreach ($this->newColumnOrder as $column) {
                $reorderedRow[] = $row[$headerIndexMap[$column]];
            }
            $reorderedRows[] = $reorderedRow;
        }

        $this->writeCsv($outputFile, $reorderedRows);

    }
}