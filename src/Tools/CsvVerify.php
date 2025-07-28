<?php
namespace Root\App\Tools;
use Root\App\Tools\Utils\SignCsvService;

class CsvVerify extends CsvProcessor
{
    private SignCsvService $signCsvService;
    private array $columnsToVerify;
    public function __construct(SignCsvService $signCsvService, array $columnsToVerify = [])
    {
        $this->signCsvService = $signCsvService;
        $this->columnsToVerify = $columnsToVerify;
    }

    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $header = array_shift($rows);
        $resultRows = [$header];
        foreach ($rows as $row) {
            $newRow = [];
            foreach ($row as $key => $cell) {
                $columnName = $header[$key];
                if (in_array($columnName, $this->columnsToVerify)) {
                    $newRow[] = $this->signCsvService->verify(implode(',', $row), $cell);
                } else {
                    $newRow[] = $cell;
                }
            }
            $resultRows[] = $newRow;
        }
        $this->writeCsv($outputFile, $resultRows);
    }
}