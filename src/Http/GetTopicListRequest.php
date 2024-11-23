<?php

namespace Vinkas\Discourse\Http;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetTopicListRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected readonly string $filter) {
      //
    }

    public function resolveEndpoint(): string
    {
        return '/'.$this->filter.'.json';
    }
}
