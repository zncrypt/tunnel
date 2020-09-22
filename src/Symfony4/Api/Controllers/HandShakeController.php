<?php

namespace ZnCrypt\BaseTunnel\Symfony4\Api\Controllers;

use ZnCrypt\Base\Domain\Enums\HashAlgoEnum;
use ZnCrypt\Pki\Domain\Libs\Rsa\Rsa;
use ZnCrypt\BaseTunnel\Domain\Libs\Session;
use ZnLib\Rest\Base\BaseCrudApiController;
use ZnBundle\Article\Domain\Interfaces\PostServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HandShakeController
{

    private $rsa;
    private $session;

    public function __construct(Rsa $rsa, Session $session)
    {
        $this->rsa = $rsa;
        $this->session = $session;
    }

    public function startSession(Request $request) {
        $this->session->start();
        $sessionId = $this->session->getSessionId();
        $certificate = $this->rsa->getCertificate()->getBase64();
        $timestamp = time();
        $clientRandomKey = $request->request->get('clientRandomKey');
        $dataForSing = $sessionId . $certificate . $clientRandomKey . $timestamp;
        $signatureEntity = $this->rsa->sign($dataForSing, HashAlgoEnum::SHA256);
        return new JsonResponse([
            'sessionId' => $sessionId,
            'certificate' => $certificate,
            'clientRandomKey' => $clientRandomKey,
            'timestamp' => $timestamp,
            'signature' => [
                'signature' => $signatureEntity->getSignatureBase64(),
                'format' => 'base64',
                'algorithm' => 'sha256',
            ],
        ]);
    }

    public function setSecretKey(Request $request)
    {
        $encryptedSecretKey = $request->request->get('encryptedSecretKey');
        $encryptedSecretMac = $request->request->get('encryptedSecretMac');
        $sessionId = $request->request->get('sessionId');
        $this->session->start($sessionId);
        if(empty($encryptedSecretKey) || empty($encryptedSecretMac)) {
            return new JsonResponse([], 422);
        }
        $secretKey = $this->rsa->decode($encryptedSecretKey);
        $secretMac = $this->rsa->decode($encryptedSecretMac);
        if(empty($secretKey) || empty($secretMac)) {
            return new JsonResponse([], 422);
        }
        $this->session->set('secretKey', $secretKey);
        $this->session->set('secretMac', $secretMac);

        return new Response();
    }

}
