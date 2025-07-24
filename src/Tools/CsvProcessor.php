<?php
namespace Root\App\Tools;
use Exception;
abstract class CsvProcessor implements ToolProcessInterface
{
    protected function readCsv(string $path): array
    {
        if (!file_exists($path)) {
            throw new Exception("File not found: $path");
        }

        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }

    protected function writeCsv(string $path, array $rows): void
    {
        if (($handle = fopen($path, 'w')) !== false) {
            foreach ($rows as $row) {
                fputcsv($handle, $row, ',', '"', '\\');
            }
            fclose($handle);
        } else {
            throw new Exception("Unable to write to file: $path");
        }
    }

    abstract public function process(string $inputFile, string $outputFile): void;
}