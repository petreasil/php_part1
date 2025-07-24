<?php
namespace Root\App\Tools;
use Exception;
use Symfony\Component\String\UnicodeString;

class CsvTruncateColumn extends CsvProcessor
{
    private int $maxLength;

    public function __construct(int $maxLength = 50)
    {
        $this->maxLength = $maxLength;
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $resultRows = [];
        foreach ($rows as $row) {
            $newRow = [];
            foreach ($row as $cell) {
                $text = new UnicodeString($cell);
                $newRow[] = $text->truncate($this->maxLength, '…')->toString();
            }
            $resultRows[] = $newRow;
        }
        $this->writeCsv($outputFile, $resultRows);
    }
}
