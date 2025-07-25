<?php
namespace Root\App\Tools\utils;
use Exception;
class EncryptionService
{
    private string $publicKey;
    private string $privateKey;


    public function __construct(string $publicKey, string $privateKey)
    {
        if (empty($publicKey) || empty($privateKey)) {
            throw new Exception("Public and Private keys must be provided.");
        }
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }


    public function encrypt(string $data): string|false
    {
        $key = openssl_pkey_get_public($this->publicKey);

        if ($key === false) {
            throw new \RuntimeException("Invalid public key.");
        }
        $encrypted = '';
        if (!openssl_public_encrypt($data, $encrypted, $key, OPENSSL_PKCS1_OAEP_PADDING)) {

            return false;
        }
        return base64_encode($encrypted);
    }


    public function decrypt(string $encryptedData): string|false
    {
        $decodedEncrypted = base64_decode($encryptedData);
        if ($decodedEncrypted === false) {

            return false;
        }

        $decrypted = '';
        if (!openssl_private_decrypt($decodedEncrypted, $decrypted, $this->privateKey, OPENSSL_PKCS1_OAEP_PADDING)) {

            return false;
        }
        return $decrypted;
    }

    public function setPrivateKey(string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }

    public function setPublicKey(string $publicKey): void
    {
        $this->publicKey = $publicKey;
    }
}