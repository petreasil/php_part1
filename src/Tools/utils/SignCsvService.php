<?php
namespace Root\App\Tools\utils;

class SignCsvService
{
    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }
    public function sign(string $data): string|false
    {
        return hash_hmac('sha256', $data, $this->secretKey);
    }

    public function verify(string $data, string $signature): string|false
    {
        $expectedSignature = hash_hmac('sha256', $data, $this->secretKey);
        return hash_equals($expectedSignature, $signature);

    }
}
