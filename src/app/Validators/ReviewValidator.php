<?php

namespace VCComponent\Laravel\Review\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;
use VCComponent\Laravel\Vicoders\Core\Validators\ValidatorInterface;

class ReviewValidator extends AbstractValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'resource_type' => ['required'],
            'resource_id' => ['required'],
            'review' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'status' => ['required'],
        ],
    ];
}
