<?php

declare(strict_types=1);
namespace Silviu\CsvTools\Command;

class CommandProcessorLocator
{
    private const array COMMANDS_TO_CLASSES = [
        'csv-column-sign'        => ColumnSigner::class,
        'csv-column-sign-verify' => ColumnSignatureVerifier::class,
        'csv-headers-prepend'    => TableHeaderPrepender::class,
        'csv-merge'              => TableMerger::class,
    ];

    private Map $processors;

    public function __construct()
    {
        $this->processors = new Map();
    }

    public function getProcessorByTool(string $tool): TableProcessorInterface
    {
        if ($this->processors->hasKey($tool)) {
            return $this->processors[$tool];
        }
        $class = self::COMMANDS_TO_CLASSES[$tool];
        $this->processors[$tool] = new $class();

        return $this->processors[$tool];
    }
}
