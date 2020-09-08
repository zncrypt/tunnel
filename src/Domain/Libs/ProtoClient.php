<?php

namespace ZnCrypt\BaseTunnel\Domain\Libs;

use GuzzleHttp\Psr7\Response;
use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;
use ZnCore\Base\Enums\Http\HttpHeaderEnum;
use ZnCrypt\BaseTunnel\Domain\Interfaces\TransportInterface;
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