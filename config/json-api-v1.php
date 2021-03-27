<?php

return [
    'resolver' => \CloudCreativity\LaravelJsonApi\Resolver\ResolverFactory::class,
    'namespace' => null,
    'by-resource' => true,
    'model-namespace' => 'App\Models',
    'resources' => [
        'articles' => \App\Models\Article::class,
    ],
    'use-eloquent' => true,
    'url' => [
        'host' => null,
        'namespace' => '/api/v1',
        'name' => 'api.v1.',
    ],
    'controllers' => [
        'transactions' => true,
        'connection' => null,
    ],
    'jobs' => [
        'resource' => 'queue-jobs',
        'model' => \CloudCreativity\LaravelJsonApi\Queue\ClientJob::class,
    ],
    'encoding' => [
        'application/vnd.api+json',
    ],
    'decoding' => [
        'application/vnd.api+json',
    ],
    'providers' => [],
];
