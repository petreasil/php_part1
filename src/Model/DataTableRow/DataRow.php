<?php
declare(strict_types=1);
namespace Silviu\CsvTools\Model\DataTableRow;

use Silviu\CsvTools\Model\DataTableRow;
use Silviu\CsvTools\Model\DataTable;
use Ds\Map;
use Webmozart\Assert\Assert;

/**
 * Models a data table data row.
 * The objects of this class are immutable.
 */
readonly class DataRow extends DataTableRow
{
    private function __construct(
        private Map $row,
    ) {
        //
    }

    public static function fromArray(array $rowValues, DataTable $table): static
    {
        $headerRow = $table->getHeaderRow();
        Assert::eq(count($rowValues), $headerRow->count());

        $rowData = new Map();
        foreach (array_values($rowValues) as $i => $columnValue) {
            $rowData[$headerRow[$i]] = $columnValue;
        }

        return new static($rowData);
    }

    public function getColumn(string $columnToSign)
    {
        return $this->row[$columnToSign];
    }

    public function toArray(): array
    {
        return $this->row->values()->toArray();
    }

    /**
     * Creates another row from the current one with an added column and corresponding value
     */
    public function withAddedColumn(string $column, mixed $value): static
    {
        $map = $this->row->copy();
        $map->put($column, $value);

        return new static($map);
    }

    /**
     * Creates another row from the current one with the given column removed
     */
    public function withRemovedColumn(string $column): static
    {
        $map = $this->row->copy();
        $map->remove($column);

        return new static($map);
    }
}
