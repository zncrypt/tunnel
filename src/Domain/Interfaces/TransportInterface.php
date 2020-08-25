<?php

namespace PhpBundle\CryptTunnel\Domain\Interfaces;

interface TransportInterface
{

    public function sendRequest($encodedRequest);

}