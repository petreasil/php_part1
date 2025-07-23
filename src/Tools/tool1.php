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
        $fpIn = fopen($inputFile, 'r');
        $fpOut = fopen($outputFile, 'w');

        $originalHeader = fgetcsv($fpIn, 0, ',', '"', '\\');
        $headerIndexMap = array_flip($originalHeader);

        fputcsv($fpOut, $newColumnOrder, ',', '"', '\\');
        $headerIndexMap = array_flip($originalHeader);

        while (($row = fgetcsv($fpIn, 0, ',', '"', '\\')) !== false) {
            // Reorder columns according to $newOrder
            $reorderedRow = [];
            foreach ($newColumnOrder as $column) {
                $reorderedRow[] = $row[$headerIndexMap[$column]];
            }
            // Write reordered row to output file
            fputcsv($fpOut, $reorderedRow, ',', '"', '\\');
        }

        fclose($fpIn);
        fclose($fpOut);
        return $this;
    }

    public function removeColumn($inputFile, $outputFile, $columnToRemove)
    {
        $fpIn = fopen($inputFile, 'r');
        $fpOut = fopen($outputFile, 'w');
        $header = fgetcsv($fpIn, 0, ',', '"', '\\');
        $headerIndexMap = array_flip($header);
        $indexesToRemove = [];
        foreach ($columnToRemove as $col) {
            if (is_int($col)) {
                $indexesToRemove[] = $col;
            } elseif (isset($headerIndexMap[$col])) {
                $indexesToRemove[] = $headerIndexMap[$col];
            } else {
                throw new \Exception("Column '$col' not found.\n");
            }
        }

        $indexesToRemove = array_unique($indexesToRemove);
        rsort($indexesToRemove);

        // Create filtered header
        $filteredHeader = $header;
        foreach ($indexesToRemove as $index) {
            unset($filteredHeader[$index]);
        }
        fputcsv($fpOut, array_values($filteredHeader), ',', '"', '\\');

        // Process each row
        while (($row = fgetcsv($fpIn, 0, ',', '"', '\\')) !== false) {
            foreach ($indexesToRemove as $index) {
                unset($row[$index]);
            }
            fputcsv($fpOut, array_values($row), ',', '"', '\\');
        }
        fclose($fpIn);
        fclose($fpOut);
        return $this;
    }

}