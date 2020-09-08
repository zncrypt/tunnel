<?php

namespace ZnCrypt\BaseTunnel\Domain\Interfaces;

interface TransportInterface
{

    public function sendRequest($encodedRequest);

}