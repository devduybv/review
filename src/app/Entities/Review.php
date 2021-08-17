<?php

namespace VCComponent\Laravel\Review\Entities;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'resource_type',
        'resource_id',
        'review',
        'rating',
        'images',
        'status',
    ];
}
