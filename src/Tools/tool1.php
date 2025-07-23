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
    }
}