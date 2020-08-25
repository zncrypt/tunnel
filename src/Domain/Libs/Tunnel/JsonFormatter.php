<?php

namespace PhpBundle\CryptTunnel\Domain\Libs\Tunnel;

use PhpBundle\Crypt\Domain\Libs\Encoders\EncoderInterface;

class JsonFormatter implements EncoderInterface
{

    public static function name() {
        return 'json';
    }

    public function encode($encrypted_data)
    {
        $data = [
            "ct" => base64_encode($encrypted_data['ct']),
            "iv" => bin2hex($encrypted_data['iv']),
            "s" => bin2hex($encrypted_data['s']),
        ];
        return json_encode($data);
    }

    public function decode($jsonStr)
    {
        $json = json_decode($jsonStr, true);
        return [
            'salt' => hex2bin($json["s"]),
            'iv' => hex2bin($json["iv"]),
            'ct' => base64_decode($json["ct"]),
        ];
    }
}
