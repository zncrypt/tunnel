<?php

namespace PhpBundle\CryptTunnel\Domain\Libs\Tunnel;

use PhpBundle\Crypt\Domain\Libs\Encoders\EncoderInterface;

class StringFormatter implements EncoderInterface
{

    public static function name() {
        return 'string';
    }

    public function encode($encrypted_data)
    {
        $data = [
            "ct" => base64_encode($encrypted_data['ct']),
            "iv" => bin2hex($encrypted_data['iv']),
            "s" => bin2hex($encrypted_data['s']),
        ];
        return $data['s'] . $data['iv'] . $data['ct'];
    }

    public function decode($jsonStr)
    {
        $json = [];
        $json['s'] = substr($jsonStr, 0, 16);
        $json['iv'] = substr($jsonStr, 16, 32);
        $json['ct'] = substr($jsonStr, 32 + 16);
        return [
            'salt' => hex2bin($json["s"]),
            'iv' => hex2bin($json["iv"]),
            'ct' => base64_decode($json["ct"]),
        ];
    }
}
