<?php

namespace ZnCrypt\BaseTunnel\Domain\Libs;

use ZnCore\Base\Helpers\EnumHelper;
use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Base\Enums\Http\HttpMethodEnum;
use ZnCore\Base\Enums\Http\HttpServerEnum;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCrypt\BaseTunnel\Domain\Entities\RequestEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestProto
{

    const CRYPT_SERVER_NAME = 'HTTP_X_CRYPT';
    const CRYPT_HEADER_NAME = 'X-Crypt';
    const CRYPT_CONTENT_TYPE = 'application/x-base64';

    private $encoderInstance;

    public function __construct(EncoderInterface $encoder)
    {
        $this->encoderInstance = $encoder;
    }

    public function encodeResponse(Response $response): Response
    {
        $headers = [];
        $encodedResponse = new Response;
        foreach ($response->headers->all() as $headerKey => $headerValue) {
            $headers[$headerKey] = ArrayHelper::first($headerValue);
        }
        $payload = [
            'status' => $response->getStatusCode(),
            'headers' => $headers,
            'content' => $response->getContent(),
        ];
        $encodedContent = $this->encoderInstance->encode($payload);
        $encodedResponse->headers->set(self::CRYPT_HEADER_NAME, 1);
        $encodedResponse->setContent($encodedContent);
        return $encodedResponse;
    }

    public function prepareRequest(string $content): RequestEntity
    {
        $requestEntity = $this->decodeRequest($content);
        return $requestEntity;
    }

    private function decodeRequest(string $encodedData): RequestEntity
    {
        $payload = $this->encoderInstance->decode($encodedData);
        $requestEntity = new RequestEntity;
        EntityHelper::setAttributes($requestEntity, $payload);

        /*$uri = new Uri($payload['uri']);
        $request = new Request($payload['method'], $uri, $payload['headers']);*/
        return $requestEntity;
    }

    public function forgeServer(RequestEntity $requestEntity): array
    {
        $server = [];
        if ($requestEntity->getHeaders()) {
            foreach ($requestEntity->getHeaders() as $headerKey => $headerValue) {
                $headerKey = strtoupper($headerKey);
                $headerKey = str_replace('-', '_', $headerKey);
                $headerKey = 'HTTP_' . $headerKey;
                $server[$headerKey] = $headerValue;
            }
        }
//        $server[HttpServerEnum::REQUEST_METHOD] = HttpMethodEnum::value($requestEntity->getMethod(), HttpMethodEnum::GET);
        $server[HttpServerEnum::REQUEST_METHOD] = EnumHelper::getValue(HttpMethodEnum::class, $requestEntity->getMethod(), HttpMethodEnum::GET);
        $server[HttpServerEnum::REQUEST_URI] = $requestEntity->getUri();
        return $server;
    }

}