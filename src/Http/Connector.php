<?php

namespace Vinkas\Discourse\Http;

use Saloon\Http\Connector as SaloonConnector;

class Connector extends SaloonConnector
{
    public function __construct(protected readonly string $baseUrl) {
        //
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
