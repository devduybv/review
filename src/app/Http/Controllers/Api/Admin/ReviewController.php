<?php

namespace VCComponent\Laravel\Review\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\Review\Repositories\ReviewInterface;
use VCComponent\Laravel\Review\Transformers\ReviewTransformer;
use VCComponent\Laravel\Review\Validators\ReviewValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class ReviewController extends ApiController
{
    protected $repository;
    protected $validator;
    protected $entity;
    public function __construct(ReviewInterface $repository, ReviewValidator $validator, Request $request)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->entity = $repository->getEntity();
        $this->type = $repository->getReviewTypeFromRequest($request);
        if (config('review.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('review.auth_middleware.admin.middleware'),
                ['except' => config('review.auth_middleware.admin.except')]
            );
        } else {
            throw new Exception("Admin middleware configuration is required");
        }
        if (isset(config('review.transformers')['review'])) {
            $this->transformer = config('review.transformers.review');
        } else {
            $this->transformer = ReviewTransformer::class;
        }
    }

    public function index(Request $request)
    {
        $query = $this->entity;
        $query = $this->repository->applyQueryScope($query, 'resource_type', $this->type);
        $query = $this->repository->getFromDate($request, $query);
        $query = $this->repository->getToDate($request, $query);
        $query = $this->repository->getStatus($request, $query);

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['review'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $reviews = $query->paginate($per_page);
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->paginator($reviews, $transformer);

    }
    public function store(Request $request)
    {
        $this->validator->isValid($request, 'RULE_CREATE');
        $data = $request->all();
        $data['resource_type'] = $this->type;
        $review = $this->entity->create($data);
        return $this->response->item($review, new $this->transformer());
    }
    public function getItemResource(Request $request)
    {
        $query = $this->entity;
        $query = $this->repository->applyQueryScope($query, 'resource_type', $this->type);
        $query = $this->repository->applyQueryScope($query, 'resource_id', $request['id']);
        $query = $this->repository->getFromDate($request, $query);
        $query = $this->repository->getToDate($request, $query);
        $query = $this->repository->getStatus($request, $query);

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['review'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $reviews = $query->paginate($per_page);
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->paginator($reviews, $transformer);

    }

}
