<?php

namespace PhpBundle\CryptTunnel\Domain\Libs\Tunnel;

use PhpBundle\Crypt\Domain\Libs\Encoders\EncoderInterface;

class TokenFormatter implements EncoderInterface
{

    public static function name() {
        return 'token';
    }

    public function encode($encrypted_data)
    {
        $data = [
            "ct" => base64_encode($encrypted_data['ct']),
            "iv" => bin2hex($encrypted_data['iv']),
            "s" => bin2hex($encrypted_data['s']),
        ];
        return $data['s'] . '.' . $data['iv'] . '.' . $data['ct'];
    }

    public function decode($jsonStr)
    {
        $parts = explode('.', $jsonStr);
        $json = [];
        $json['s'] = $parts[0];
        $json['iv'] = $parts[1];
        $json['ct'] = $parts[2];
        return [
            'salt' => hex2bin($json["s"]),
            'iv' => hex2bin($json["iv"]),
            'ct' => base64_decode($json["ct"]),
        ];
    }
}
