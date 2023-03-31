<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Crypto
{
    private $cipher = "AES-128-CBC";
    private $ivlen;
    private $passphrase;  // = "commentestvotreblanquette";

    /*private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->ivlen = openssl_cipher_iv_length($this->cipher);
        $this->logger = $logger;
    }*/

    public function __construct(private readonly LoggerInterface $logger, string $passphrase, ParameterBagInterface $parameterBag)
    {
        $this->ivlen = openssl_cipher_iv_length($this->cipher);
        $this->passphrase = $passphrase;
        // dump($parameterBag->get('message'), $parameterBag->get('kernel.project_dir'));
    }

    /*public function encode(string $plaintext): string
    {
        return str_rot13($plaintext);
    }

    public function decode(string $ciphertext): string
    {
        return $this->encode($ciphertext);
    }*/

    public function encode(string $plaintext): ?string
    {
        $this->logger->info("Start encode message");
        $this->logger->info("Passphrase used: {passphrase}", ['passphrase' => $this->passphrase]);
        $iv = openssl_random_pseudo_bytes($this->ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $this->cipher, $this->passphrase, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->passphrase, true);

        $this->logger->info("Encoding finished and cipher returned");
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }

    public function decode(string $ciphertext): ?string
    {
        $decode = base64_decode($ciphertext);
        $iv = substr($decode, 0, $this->ivlen);
        $hmac = substr($decode, $this->ivlen, 32);
        $ciphertext_raw = substr($decode, $this->ivlen+32);

        $plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $this->passphrase, OPENSSL_RAW_DATA, $iv);

        $signature = hash_hmac('sha256', $ciphertext_raw, $this->passphrase, true);
        if( hash_equals($hmac, $signature) ) {
            return $plaintext;
        }

        return null;
    }
}
