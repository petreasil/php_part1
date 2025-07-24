<?php
namespace Root\App\Tools;
use Exception;
class CsvColumnRemoval extends CsvProcessor
{
    private array $columnToRemove;

    public function __construct(array $columnToRemove = [])
    {
        $this->columnToRemove = $columnToRemove;
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $header = $rows[0];
        $headerIndexMap = array_flip($header);
        $indexesToRemove = [];
        foreach ($this->columnToRemove as $col) {
            if (is_int($col)) {
                $indexesToRemove[] = $col;
            } elseif (isset($headerIndexMap[$col])) {
                $indexesToRemove[] = $headerIndexMap[$col];
            } else {
                throw new Exception("Column '$col' not found.\n");
            }
        }
        $indexesToRemove = array_unique($indexesToRemove);
        rsort($indexesToRemove);
        foreach ($rows as &$row) {
            foreach ($indexesToRemove as $index) {
                unset($row[$index]);
            }
        }
        $this->writeCsv($outputFile, $rows);
    }
}
