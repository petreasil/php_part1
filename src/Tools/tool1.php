<?php
namespace Root\App\Tools;

class tool1
{
    public function prependHeaderRow($inputFile, $outputFile, $headerRow)
    {
        $fpIn = fopen($inputFile, 'r');
        $fpOut = fopen($outputFile, 'w');

        // Write the header row
        fputcsv($fpOut, $headerRow, ',', '"', '\\');

        // Copy the rest of the file
        while (($row = fgetcsv($fpIn, 0, ',', '"', '\\')) !== FALSE) {
            fputcsv($fpOut, $row, ',', '"', '\\');
        }

        fclose($fpIn);
        fclose($fpOut);
        return $this;
    }

    public function addIndexingColumn($inputFile, $outputFile, $indexName)
    {
        $fpIn = fopen($inputFile, 'r');
        $fpOut = fopen($outputFile, 'w');

        $index = 1;
        while (($row = fgetcsv($fpIn, 0, ',', '"', '\\')) !== FALSE) {
            // Add the indexing column
            array_unshift($row, $index);
            fputcsv($fpOut, $row, ',', '"', '\\');
            $index++;
        }

        fclose($fpIn);
        fclose($fpOut);
        return $this;
    }

    public function reorderColumns($inputFile, $outputFile, $newColumnOrder)
    {
        $csv = [];
        if (($handle = fopen($inputFile, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ',');
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $csv[] = array_combine($header, $data);
            }
            fclose($handle);
        }

        $output = [];
        foreach ($csv as $row) {
            $newRow = [];
            foreach ($newColumnOrder as $column) {
                $newRow[$column] = $row[$column];
            }
            $output[] = $newRow;
        }

        $fp = fopen($outputFile, 'w');
        fputcsv($fp, $newColumnOrder);
        foreach ($output as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    }
}