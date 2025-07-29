<?php
namespace Silviu\CsvTools\Command\ArgumentConvertor;
use InvalidArgumentException;

class CsvHeadersPrependArgumentConverter extends PipingCompatibleArgumentConverter
{
    public static function initOptions(): void
    {
        GetOpt::initOptions('h:', ['headers:', 'output-format:']);
    }

    public static function extractTableProcessorConfig(): ?TableHeaderPrependerConfig
    {
        // extracts the h/headers option as configured through initialization
        $options = GetOpt::getParsedOptions();
        $headers = $options['h'] ?? $options['headers'];
        if (null === $headers) {
            throw new InvalidArgumentException('Headers must be provided');
        }
        $outputFormat = $options['output-format'] ?? TableHeaderPrependerConfig::OUTPUT_FORMAT_CSV;

        return new TableHeaderPrependerConfig(explode(',', $headers), $outputFormat);
    }
}
