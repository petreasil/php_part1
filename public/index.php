<?php

//phpinfo();

require_once __DIR__.'/../vendor/autoload.php';
use Root\App\Tools\tool1;

$inputFile = 'input.csv';
$outputFile = 'output.csv';
$headerRow = [ 'TASK', 'NAME', 'EMAIL', 'STATUS', 'CREATED_AT', 'UPDATED_AT', 'DELETED_AT',"FILED1","FILED2"];
try {
    $tool1 = new tool1($inputFile, $outputFile, $headerRow);
    $tool1->prependHeader();
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Done";