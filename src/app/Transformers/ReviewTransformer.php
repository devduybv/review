<?php

namespace VCComponent\Laravel\Review\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\Post\Transformers\PostTransformer;
use VCComponent\Laravel\Product\Transformers\ProductTransformer;

class ReviewTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'posts',
        'products',
    ];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id' => (int) $model->id,
            'resource_type' => $model->resource_type,
            'resource_id' => (int) $model->resource_id,
            'review' => $model->review,
            'rating' => (int) $model->rating,
            'images' => $model->images,
            'status' => (int) $model->status,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includePosts($model)
    {
        return $this->collection($model->Posts, new PostTransformer());
    }
    public function includeProducts($model)
    {
        return $this->collection($model->products, new ProductTransformer());
    }
}
