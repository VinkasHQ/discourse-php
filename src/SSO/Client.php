<?php

namespace Vinkas\Discourse\PHP\SSO;

class Client
{

  protected $nonce_key = 'nonce';

  protected $payload_response_key = 'sso';
  protected $signature_response_key = 'sig';

  private $secret;
  private $payload;
  private $signature;
  private $nonce;

  public function __construct($secret, $payload, $signature) {
    $this->secret = $secret;
    $this->payload = $payload;
    $this->signature = $signature;
  }

  protected function getSecret() {
    return $this->secret;
  }

  protected function getPayload() {
    return $this->payload;
  }

  protected function getSignature() {
    return $this->signature;
  }

  public function isValid() {
    return ($this->getNonceFromPayload() && ($this->getRequestPayloadSignature() === $this->getSignature()));
  }

  protected function getNonceFromPayload()
  {
    if ($this->nonce) {
      return $this->nonce;
    }
    $payloads = [];
    parse_str(base64_decode($this->getDecodedPayload()), $payloads);
    if (!array_key_exists($this->nonce_key, $payloads)) {
      return false;
    }
    $this->nonce = $payloads[$this->nonce_key];
    return $this->nonce;
  }

  protected function getDecodedPayload()
  {
    return urldecode($this->getPayload());
  }

  protected function getRequestPayloadSignature()
  {
    return hash_hmac('sha256', $this->getDecodedPayload(), $this->getSecret());
  }

  protected function getResponseUrl($callbackUrl, array $userParams)
  {
    return $callbackUrl . '?' . $this->getResponseQuery($userParams);
  }

  protected function getResponseQuery(array $userParams)
  {
    $response = [
      $this->payload_response_key   => $this->getResponsePayload($userParams),
      $this->signature_response_key => $this->getResponsePayloadSignature(),
    ];
    return http_build_query($response);
  }

  protected function getResponsePayload(array $userParams)
  {
    $params =  ['nonce' => $this->getNonceFromPayload()];
    $params = array_merge($userParams, $params);
    return base64_encode(http_build_query($params));
  }

  protected function getResponsePayloadSignature()
  {
    return hash_hmac('sha256', $this->getResponsePayload(), $this->getSecret());
  }

}