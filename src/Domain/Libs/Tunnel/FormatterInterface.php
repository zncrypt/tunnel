<?php

namespace PhpBundle\CryptTunnel\Domain\Libs\Tunnel;

interface FormatterInterface
{

    public static function name();

    public static function encode($encrypted_data, string $iv, string $salt);

    public static function decode(string $encoded);
}
