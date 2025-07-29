<?php


declare(strict_types=1);

namespace Silviu\CsvTools\TableProcessor\Config;

use Silviu\CsvTools\TableProcessor\TableProcessorConfigInterface;
use OpenSSLAsymmetricKey;

readonly class ColumnSignatureVerifierConfig implements TableProcessorConfigInterface
{
    public function __construct(
        private string               $column,
        private string               $signatureColumn,
        private OpenSSLAsymmetricKey $publicKey,
    )
    {
        //
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getSignatureColumn(): string
    {
        return $this->signatureColumn;
    }

    public function getPublicKey(): OpenSSLAsymmetricKey
    {
        return $this->publicKey;
    }
}
