<?php
declare(strict_types=1);
namespace Silviu\CsvTools\TableProcessor;

interface ConfigurableInterface
{
    public function configure(TableProcessorConfigInterface $config): static;
}
