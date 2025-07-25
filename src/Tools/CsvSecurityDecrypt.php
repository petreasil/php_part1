<?php
namespace Root\App\Tools;
use Exception;
use Root\App\Tools\utils\EncryptionService;

class CsvSecurityDecrypt extends CsvProcessor
{
    private array $columnsToDecrypt = [];

    public function __construct(private EncryptionService $encryptionService, array $columnsToDecrypt = [])
    {
        $this->columnsToDecrypt = $columnsToDecrypt;
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
                if (in_array($columnName, $this->columnsToDecrypt)) {
                    $newRow[] = $this->encryptionService->decrypt($cell);
                } else {
                    $newRow[] = $cell;
                }
            }
            $resultRows[] = $newRow;
        }
        $this->writeCsv($outputFile, $resultRows);
    }

}