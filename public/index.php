<?php

// phpinfo();

require_once __DIR__ . '/../vendor/autoload.php';
use Root\App\Tools\tool1;

$inputFile = 'input.csv';
$outputFile = 'output.csv';
$headerRow = ['TASK', 'NAME', 'EMAIL', 'STATUS', 'CREATED_AT', 'UPDATED_AT', 'DELETED_AT', "FILED1", "FILED2"];
$reorder = ['DELETED_AT', "FILED1", "FILED2", 'TASK', 'NAME', 'EMAIL', 'STATUS', 'CREATED_AT', 'UPDATED_AT',];
$tool1 = new tool1();
try {
    $tool1->prependHeaderRow($inputFile, $outputFile, $headerRow);
} catch (Exception $e) {
    echo $e->getMessage();
}
echo 'Done';

// try {
//     $tool1->addIndexingColumn($inputFile, $outputFile, $headerRow);
// } catch (Exception $e) {
//     echo $e->getMessage();
// }
// echo 'Done index column';

try {
    $tool1->reorderColumns($inputFile, $outputFile, $reorder);
} catch (Exception $e) {
    echo $e->getMessage();
}
echo 'Done reorder columns';
