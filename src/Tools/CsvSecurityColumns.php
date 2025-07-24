<?php
namespace Root\App\Tools;
use Exception;
use Root\App\Tools\utils\EncryptionService;
class CsvSecurityColumns extends CsvProcessor
{
    private array $columnsToEncrypt = [];


    public function __construct(private EncryptionService $encryptionService, array $columnsToEncrypt = [], private string $publicKey, private string $privateKey)
    {
        $this->columnsToEncrypt = $columnsToEncrypt;
        $this->encryptionService->setPublicKey($this->publicKey);
        $this->encryptionService->setPrivateKey($this->privateKey);
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        $resultRows = [];
        foreach ($rows as $row) {
            $newRow = [];
            foreach ($row as $key => $cell) {
                $newRow[] = in_array($key, $this->columnsToEncrypt) ? $this->encryptionService->encrypt($cell) : $cell;
            }
            $resultRows[] = $newRow;
        }
        $this->writeCsv($outputFile, $resultRows);

    }

}