<?php
namespace Root\App\Tools;
use Exception;

class CsvInnerJoin extends CsvProcessor
{
    private array $columnsToJoin = [];

    public function __construct(array $columnsToJoin = [])
    {
        $this->columnsToJoin = $columnsToJoin;
    }
    public function join(string $inputFile1, string $inputFile2, string $outputFile)
    {
        $rows1 = $this->readCsv($inputFile1);
        $rows2 = $this->readCsv($inputFile2);
        if (empty($rows1) || empty($rows2)) {
            throw new Exception("One of the input files is empty.");
        }
        $header1 = array_shift($rows1);
        $header2 = array_shift($rows2);
        $result = [];
        $lowerHeader1 = array_map('strtolower', $header1);
        $lowerHeader2 = array_map('strtolower', $header2);
        $uniqueColumnsToJoin = array_unique(array_merge($this->columnsToJoin, array_intersect($lowerHeader1, $lowerHeader2)));
        $indexes1 = [];
        $indexes2 = [];
        foreach ($uniqueColumnsToJoin as $column) {
            $index1 = array_search($column, $lowerHeader1);
            $index2 = array_search($column, $lowerHeader2);

            if ($index1 === false || $index2 === false) {
                throw new Exception("Join column '$column' not found in both input files.");
            }

            $indexes1[] = $index1;
            $indexes2[] = $index2;
        }
        $joinedHeader = $header1;
        foreach ($header2 as $i => $col) {
            if (!in_array(strtolower($col), $uniqueColumnsToJoin)) {
                $joinedHeader[] = $col;
            }
        }

        $result = [$joinedHeader];

        foreach ($rows1 as $row1) {
            foreach ($rows2 as $row2) {
                $match = true;
                foreach ($indexes1 as $i => $index1) {
                    $index2 = $indexes2[$i];
                    if (trim(strtolower($row1[$index1])) !== trim(strtolower($row2[$index2]))) {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    $mergedRow = $row1;
                    foreach ($row2 as $j => $value) {
                        if (!in_array($j, $indexes2)) {
                            $mergedRow[] = $value;
                        }
                    }
                    $result[] = $mergedRow;
                }
            }
        }
        $this->writeCsv($outputFile, $result);
    }
    public function process(string $inputFile1, string $outputFile): void
    {
        throw new Exception("Use mergeCsv() instead of process()");
    }
}