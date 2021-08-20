<?php

return [
    'namespace' => env('REVIEW_COMPONENT_NAMESPACE', 'review-management'),
    'models' => [
        'review' => VCComponent\Laravel\Review\Entities\Review::class,
    ],

    'transformers' => [
        'review' => VCComponent\Laravel\Review\Transformers\ReviewTransformer::class,
    ],
    'auth_middleware' => [
        'admin'    => [
            'middleware' => '',
            'except'     => [],
        ]
    ]
];
