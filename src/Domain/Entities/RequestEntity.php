<?php

namespace PhpBundle\CryptTunnel\Domain\Entities;

use PhpLab\Core\Enums\Http\HttpMethodEnum;

// todo: implement PSR

class RequestEntity
{

    private $headers = [];
    private $method = HttpMethodEnum::GET;
    private $uri = '/';
    private $query = [];
    private $body;

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method): void
    {
        $this->method = $method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri): void
    {
        $this->uri = $uri;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query): void
    {
        $this->query = $query;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body): void
    {
        $this->body = $body;
    }

}