<?php

// phpinfo();

require_once __DIR__ . '/../vendor/autoload.php';
use Root\App\Tools\CsvPrependHeader;
use Root\App\Tools\CsvAddIndexColumn;
use Root\App\Tools\CsvColumnRemoval;
use Root\App\Tools\CsvReorderColumn;
use Root\App\Tools\CsvTruncateColumn;
use Root\App\Tools\CsvReformatDate;
use Root\App\Tools\CsvMergeFiles;
use Root\App\Tools\CsvSecurityDecrypt;
use Root\App\Tools\CsvSecurityEncrypt;
use Root\App\Tools\utils\EncryptionService;
use Root\App\Tools\utils\SignCsvService;
use Root\App\Tools\CsvSignColumn;
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
$columnsToEncrypt = ["FILED1", "FILED2"];

//keys for encryption

$config = [
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];
$privateKeyResource = openssl_pkey_new($config);
openssl_pkey_export($privateKeyResource, $privateKey);

// Extract the public key
$keyDetails = openssl_pkey_get_details($privateKeyResource);
$publicKey = $keyDetails["key"];

file_put_contents("private_key.pem", $privateKey);
file_put_contents("public_key.pem", $publicKey);
//end keys

//SIGN CSV

$secretKey = "secretKey";
$columnsToSign = ["FILED1", "FILED2"];
$sign = new SignCsvService($secretKey);
$neihth = new CsvSignColumn($sign, $columnsToSign);

try {
    $neihth->process($inputFile, "outputSign.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done sign csv.<br>";

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

$seventh = new CsvMergeFiles();
try {
    $seventh->mergeCsv($inputFile, $inputFile2, "outputMerge.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done merge files.<br>";

$encryptionService = new EncryptionService($publicKey, $privateKey);

$eighth = new CsvSecurityEncrypt($encryptionService, $columnsToEncrypt, );
try {
    $eighth->process($inputFile, "outputSecurity.csv");

} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done encrypt columns.<br>";

try {
    $tenth = new CsvSecurityDecrypt($encryptionService);
    $tenth->process("outputSecurity.csv", "outputSecurityDecrypt.csv");
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "Done decrypt columns.<br>";
