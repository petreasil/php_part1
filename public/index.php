<?php

// phpinfo();

require_once __DIR__ . '/../vendor/autoload.php';
use Root\App\Tools\CsvPrependHeader;
use Root\App\Tools\CsvAddIndexColumn;
use Root\App\Tools\CsvColumnRemoval;
use Root\App\Tools\CsvReorderColumn;
use Root\App\Tools\CsvTruncateColumn;
use Root\App\Tools\CsvReformatDate;
// use Root\App\Tools\CsvReorderColumns;
// use Root\App\Tools\CsvRemoveColumn;
// use Root\App\Tools\Tool1;


$inputFile = 'input.csv';
$inputFile2 = 'input2.csv';
$outputFile = 'output.csv';
$headerRow = ['TASK', 'NAME', 'EMAIL', 'STATUS', 'CREATED_AT', 'UPDATED_AT', 'DELETED_AT', "FILED1", "FILED2"];
$headerId = ["ID"];
$reorder = ['DELETED_AT', "FILED1", "FILED2", 'TASK', 'NAME', 'EMAIL', 'STATUS', 'CREATED_AT', 'UPDATED_AT',];
$remove = ["FILED1", "FILED2"];

$first = new CsvPrependHeader($headerRow);
try {
    $first->process($inputFile, $outputFile);
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done prepend header.<br>";

$second = new CsvAddIndexColumn($headerId);
try {
    $second->process($inputFile, "outputIndex.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo 'Done index column.<br>';

$third = new CsvReorderColumn($reorder);
try {
    $third->process($inputFile, "outputReorder.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done reorder columns. <br>";

$fourth = new CsvColumnRemoval($remove);
try {
    $fourth->process($inputFile, "outputRemove.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done remove columns.";

$fifth = new CsvTruncateColumn(5);
try {
    $fifth->process($inputFile, "outputTruncate.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done truncate columns.<br>";

$sixth = new CsvReformatDate('Y-m-d');
try {
    $sixth->process($inputFile2, "outputReformatDate.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done reformat date.<br>";
