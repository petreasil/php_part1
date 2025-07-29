<?php


declare(strict_types=1);

namespace Silviu\CsvTools\TableProcessor\Processor;

use Silviu\CsvTools\Model\DataTable;
use Silviu\CsvTools\TableProcessor\Config\TableHeaderPrependerConfig;
use Silviu\CsvTools\TableProcessor\ConfigurableInterface;
use Silviu\CsvTools\TableProcessor\TableProcessorConfigInterface;
use Silviu\CsvTools\TableProcessor\TableProcessorInterface;
use InvalidArgumentException;

class TableHeaderPrepender implements TableProcessorInterface, ConfigurableInterface
{
    private TableHeaderPrependerConfig $config;

    public function process(DataTable ...$tables): array
    {
        $results = [];
        foreach ($tables as $table) {
            $result = DataTable::createEmpty($this->config->getHeaders());
            $result->appendRowsFrom($table);
            $results[] = $result;
        }

        return $results;
    }

    public function configure(TableProcessorConfigInterface $config): static
    {
        if (!$config instanceof TableHeaderPrependerConfig) {
            throw new InvalidArgumentException('Invalid config type');
        }
        $this->config = $config;

        return $this;
    }
}
