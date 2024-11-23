<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Vinkas\Discourse\Http\Connector;
use Vinkas\Discourse\Http\GetTopicListRequest;

test('requests topic list from Discourse', function () {
    MockClient::global([
        '*' => MockResponse::make('Not Found', 404),
        GetTopicListRequest::class => MockResponse::fixture('topic_list'),
    ]);

    $connector = new Connector('https://discourse.example.com');
    $request = new GetTopicListRequest('latest');
    $response = $connector->send($request);
    expect($response->status())->toEqual(200);

    $data = $response->json();
    expect($data['users'])->toHaveCount(1);
    expect($data['topic_list']['topics'])->toHaveCount(3);
});
