<?php
namespace Root\App\Tools;

use Exception;

class CsvReformatDate extends CsvProcessor implements ToolProcessInterface
{
    private string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }
    public function process(string $inputFile, string $outputFile): void
    {

    }
}