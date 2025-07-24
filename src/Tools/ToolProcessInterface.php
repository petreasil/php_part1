<?php
namespace Root\App\Tools;
interface ToolProcessInterface
{
    public function process(string $inputFile, string $outputFile): void;
}