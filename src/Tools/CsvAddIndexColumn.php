<?php
namespace Root\App\Tools;
class CsvAddIndexColumn extends CsvProcessor
{
    private array $headerId;

    public function __construct(array $headerId = [])
    {
        $this->headerId = $headerId;
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $header = array_shift($rows);
        array_unshift($header, $this->headerId[0]);
        $rowsWithIndex = [$header];
        $index = 1;
        foreach ($rows as $row) {
            array_unshift($row, $index);
            $rowsWithIndex[] = $row;
            $index++;
        }
        $this->writeCsv($outputFile, $rowsWithIndex);
    }
}