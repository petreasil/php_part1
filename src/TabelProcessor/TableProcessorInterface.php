<?php


declare(strict_types=1);

namespace Silviu\CsvTools\TableProcessor;

use Silviu\CsvTools\Model\DataTable;

interface TableProcessorInterface
{
    /**
     * @return array|DataTable[]
     */
    public function process(DataTable ...$tables): array;
}
