<?php

namespace Root\App\Tools;
interface SecurityProcessorInterface extends ToolProcessInterface
{
    public function decryptProcess(string $inputFile, string $outputFile): void;
}