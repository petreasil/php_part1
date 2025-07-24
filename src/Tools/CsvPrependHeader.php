<?php
namespace Root\App\Tools;
class CsvPrependHeader extends CsvProcessor
{
    private array $headerRow;

    public function __construct(array $headerRow)
    {
        $this->headerRow = $headerRow;
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        array_unshift($rows, $this->headerRow);
        $this->writeCsv($outputFile, $rows);
    }
}