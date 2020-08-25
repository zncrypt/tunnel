<?php

namespace PhpBundle\CryptTunnel\Domain\Libs\Encoders;

use PhpBundle\Crypt\Domain\Enums\EncryptAlgorithmEnum;
use PhpLab\Core\Helpers\StringHelper;
use phpseclib\Crypt\AES;
use phpseclib\Crypt\Base;

class AesAdvancedEncoder implements EncoderInterface
{

    private $formatter;
    private $password;
    private $macSecret = '1234567890';

    public function __construct(string $password = null, object $formatter = null)
    {
        $this->formatter = $formatter;
        $this->password = $password;
    }

    public function setFormatter(object $formatter) {
        $this->formatter = $formatter;
    }

    public function encode($value)
    {
        $password = $this->password;
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $password . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = [
            "ct" => $encrypted_data,
            "iv" => $iv,
            "s" => $salt,
        ];
        $encoded = $this->formatter->encode($data);
        return StringHelper::utf8ize($encoded);
    }

    public function decode($jsonStr)
    {
        $password = $this->password;
        $decoded = $this->formatter->decode($jsonStr);
        //$decoded['ct'] = $this->verify($decoded['ct']);
        $concatedPassphrase = $password . $decoded['salt'];
        $md5 = [];
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($decoded['ct'], 'aes-256-cbc', $key, true, $decoded['iv']);
        return json_decode($data, true);
    }

    public function sign($ct)
    {
        $signature = hash_hmac(EncryptAlgorithmEnum::SHA512, $ct, $this->macSecret);
        return $signature;
    }

    public function verify($ct, $ctSignature)
    {
        $signature = hash_hmac(EncryptAlgorithmEnum::SHA512, $ct, $this->macSecret);
        if($signature !== $ctSignature) {
            throw new \Exception('Invalid signature!');
        }
    }
}
