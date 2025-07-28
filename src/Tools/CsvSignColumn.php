<?php
namespace Root\App\Tools;
use Exception;
use Root\App\Tools\Utils\SignCsvService;

class CsvSignColumn extends CsvProcessor
{
    private SignCsvService $signCsvService;
    private array $columnsToSign;

    public function __construct(SignCsvService $signCsvService, array $columnsToSign = [])
    {
        $this->signCsvService = $signCsvService;
        $this->columnsToSign = $columnsToSign;
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
                if (in_array($columnName, $this->columnsToSign)) {
                    $newRow[] = $this->signCsvService->sign(implode(',', $row));
                } else {
                    $newRow[] = $cell;
                }
            }
            $resultRows[] = $newRow;
        }
        $this->writeCsv($outputFile, $resultRows);
    }

}
