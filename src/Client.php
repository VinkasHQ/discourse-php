<?php

namespace Vinkas\Discourse;

class Client
{
  public function __construct(protected readonly string $baseUrl) {
    //
  }

  public function getBaseUrl() {
    return $this->baseUrl;
  }

  public function connect($secret, $payload = null, $signature = null) {
    return new Connect($this->getBaseUrl(), $secret, $payload, $signature);
  }

  public function api() {
    return new Http\Connector($this->getBaseUrl());
  }
}
