<?php
namespace Root\App\Tools;
use Exception;
use Root\App\Tools\utils\SignCsvService;

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

    public function processVerify(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $header = array_shift($rows);
        $resultRows = [$header];
        foreach ($rows as $row) {
            $newRow = [];
            foreach ($row as $key => $cell) {
                $columnName = $header[$key];
                if (in_array($columnName, $this->columnsToSign)) {
                    $signatureIndex = array_search($columnName, $this->columnsToSign);
                    $dataToVerify = $row[$key];
                    $expectedSignature = $row[$this->columnsToSign[0]];
                    $newRow[] = $this->signCsvService->verify($cell);
                } else {
                    $newRow[] = $cell;
                }
            }
            $resultRows[] = $newRow;
        }
        $this->writeCsv($outputFile, $resultRows);
    }
}
