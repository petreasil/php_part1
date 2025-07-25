<?php
namespace Root\App\Tools;
use Exception;
use Root\App\Tools\utils\EncryptionService;
class CsvSecurityColumns extends CsvProcessor
{
    private array $columnsToEncrypt = [];

    public function __construct(private EncryptionService $encryptionService, array $columnsToEncrypt = [])
    {
        $this->columnsToEncrypt = $columnsToEncrypt;
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $header = array_shift($rows);
        $headerIndexMap = array_flip($header);
        $resultRows = [$header];
        foreach ($rows as $row) {
            $newRow = [];
            foreach ($row as $key => $cell) {
                $columnName = $header[$key];
                $newRow[] = in_array($columnName, $this->columnsToEncrypt) ? $this->encryptionService->encrypt($cell) : $cell;
            }
            $resultRows[] = $newRow;
        }
        $this->writeCsv($outputFile, $resultRows);

    }

}