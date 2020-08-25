<?php

namespace PhpBundle\CryptTunnel\Domain\Libs;

use GuzzleHttp\Psr7\Response;
use PhpBundle\Crypt\Domain\Libs\Encoders\EncoderInterface;
use PhpLab\Core\Enums\Http\HttpHeaderEnum;
use PhpBundle\CryptTunnel\Domain\Interfaces\TransportInterface;
use Psr\Http\Message\ResponseInterface;

class ProtoClient
{

    private $transport;
    private $encoder;

    public function __construct(TransportInterface $transport, EncoderInterface $encoder)
    {
        $this->transport = $transport;
        $this->encoder = $encoder;
    }

    public function request(string $method, string $uri, array $query = [], array $body = []): ResponseInterface
    {
        $dataForEncode = [
            'method' => $method,
            'uri' => $uri,
            'headers' => [
                HttpHeaderEnum::CONTENT_TYPE => 'application/x-base64',
            ],
            'query' => $query,
        ];
        $encodedRequest = $this->encoder->encode($dataForEncode);
        $encodedContent = $this->transport->sendRequest($encodedRequest);
        $payload = $this->encoder->decode($encodedContent);
        return new Response($payload['statusCode'], $payload['headers'], $payload['content']);
    }

}