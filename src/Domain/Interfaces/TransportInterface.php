<?php

namespace ZnCrypt\Tunnel\Domain\Interfaces;

interface TransportInterface
{

    public function sendRequest($encodedRequest);

}