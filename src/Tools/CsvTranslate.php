<?php
namespace Root\App\Tools;
use Exception;
use Root\App\Tools\Utils\HttpService;

class CsvTranslate extends CsvProcessor
{
    private $columnsToTranslate = [];
    private HttpService $httpService;
    public function __construct(HttpService $httpService, array $columnsToTranslate = [])
    {
        $this->httpService = $httpService;
        $this->columnsToTranslate = $columnsToTranslate;
    }

    public function process(string $inputFile, string $outputFile): void
    {
        $rows = $this->readCsv($inputFile);
        if (empty($rows)) {
            throw new Exception('No data found in input file');
        }
        $header = $rows[0];
        $headerIndexMap = array_flip($header);
        $columnsToTranslateIndexes = [];
        foreach ($this->columnsToTranslate as $column) {
            if (isset($headerIndexMap[$column])) {
                $columnsToTranslateIndexes[] = $headerIndexMap[$column];
            }
        }
        $dataToTranslate = [];
        foreach ($rows as $row) {
            foreach ($columnsToTranslateIndexes as $index) {
                $dataToTranslate[] = $row[$index];
            }
        }
        $translatedValues = $this->translateRows($dataToTranslate);

        $counter = 0;
        foreach ($rows as $i => &$row) {
            if ($i === 0)
                continue; // Skip header
            foreach ($columnsToTranslateIndexes as $index) {
                if (isset($translatedValues[$counter])) {
                    $row[$index] = $translatedValues[$counter];
                }
                $counter++;
            }
        }
        $this->writeCsv($outputFile, $rows);
    }

    private function translateRows(array $dataToTranslate): array
    {
        $translated = [];

        foreach ($dataToTranslate as $text) {
            try {
                $translated[] = $this->callFtApiTranslate($text, 'ro', 'en');
            } catch (Exception $e) {
                $translated[] = $text; // Fallback to original if fails
            }
        }

        return $translated;
    }

    private function callFtApiTranslate(string $text, string $targetLang, ?string $sourceLang = null, ): string
    {
        $params = [
            'text' => $text,
            'dl' => $targetLang,
        ];
        if ($sourceLang) {
            $params['sl'] = $sourceLang;
        }

        $url = 'https://ftapi.pythonanywhere.com/translate?' . http_build_query($params);

        $response = $this->httpService->get($url);

        $data = json_decode($response, true);

        if (!isset($data['destination-text'])) {
            throw new Exception("Translation API failed or returned invalid data: $response");
        }

        return rtrim($data['destination-text'], "\n");
    }

}
