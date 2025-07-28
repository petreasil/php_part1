<?php

namespace Root\App\Tools;
use Exception;


class CsvSelect extends CsvProcessor
{
    private array $selectedColumns = [];
    private array $whereConditions = [];

    public function selectColumns(array $columns): self
    {
        $this->selectedColumns = array_values($columns);
        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->whereConditions[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        return $this;
    }
    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        if (empty($rows)) {
            throw new Exception('No data found in input file');
        }
        $headers = array_keys($rows[0]);
        $filteredRows = $this->filterRows($rows);
        $selectedRows = $this->setSelectedColumns($filteredRows, $headers);

        $this->writeCsv($outputFile, $selectedRows);
    }

    private function filterRows(array $rows): array
    {
        if (empty($this->whereConditions)) {
            return $rows;
        }
        $filteredRows = [];
        foreach ($rows as $row) {
            $matchesConditions = true;
            foreach ($this->whereConditions as $condition) {
                $columnValue = $row[$condition['column']] ?? null;
                $result = $this->evaluateCondition($columnValue, $condition['operator'], $condition['value']);
                if (!$result) {
                    $matchesConditions = false;
                    break;
                }
            }
            if ($matchesConditions) {
                $filteredRows[] = $row;
            }
        }
        return $filteredRows;

    }

    private function evaluateCondition($value, string $operator, $expectedValue): bool
    {
        switch ($operator) {
            case '=':
                return $value === $expectedValue;
            case '>':
                return $value > $expectedValue;
            case '<':
                return $value < $expectedValue;
            case 'LIKE':
                return preg_match('/' . $expectedValue . '/', $value);
            default:
                throw new Exception("Unsupported operator: $operator");
        }
    }
    private function setSelectedColumns(array $rows, array $headers): array
    {
        if (empty($this->selectedColumns)) {
            return $rows;
        }

        $normalizedHeaders = array_change_key_case(array_flip($headers), CASE_LOWER); // [name => 0, email => 1, ...]
        $selectedRows = [];

        foreach ($rows as $row) {
            $selectedRow = [];
            foreach ($this->selectedColumns as $column) {
                $normalizedColumn = strtolower(trim($column));
                if (!isset($normalizedHeaders[$normalizedColumn])) {
                    throw new Exception("Column not found: $column");
                }

                // Original header name (case-sensitive)
                $originalColumn = $headers[$normalizedHeaders[$normalizedColumn]];
                $selectedRow[$originalColumn] = $row[$originalColumn] ?? null;
            }
            $selectedRows[] = $selectedRow;
        }

        return $selectedRows;
    }

}