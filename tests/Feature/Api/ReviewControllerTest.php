<?php

namespace VCComponent\Laravel\Review\Test\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Review\Entities\Review;
use VCComponent\Laravel\Review\Test\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_from_field_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => '', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_from_field()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'test', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_field_from_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'from' => ''];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_with_from_date_router()
    {
        $reviews = factory(Review::class, 5)->create(['created_at' => '01/08/2021'])->toArray();
        foreach ($reviews as $reviews) {
            unset($reviews['updated_at']);
            unset($reviews['created_at']);
        }
        $data = ['field' => 'created', 'from' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $reviews,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

        $response->assertStatus(200);

    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_field_from()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'from' => '3/8/2021'];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_to_field_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => '', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_to_field()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'test', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_field_to_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'to' => ''];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_field_to()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'to' => '3/8/2021'];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_with_to_date_router()
    {
        $reviews = factory(Review::class, 5)->create()->toArray();
        foreach ($reviews as $reviews) {
            unset($reviews['updated_at']);
            unset($reviews['created_at']);
        }
        $data = ['field' => 'created', 'to' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $reviews,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

        $response->assertStatus(200);

    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_with_status_router()
    {
        $reviews = factory(Review::class, 5)->create()->toArray();
        $data = ['status' => ''];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $this->assertRequired($response, 'The input status is incorrect');
    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_with_status_router()
    {
        $reviews = factory(Review::class, 5)->create()->toArray();
        factory(Review::class, 5)->create(['status' => 2])->toArray();
        $data = ['status' => 1];
        $response = $this->json('GET', '/api/reviews/posts', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 1,
        ]);
        $response->assertJsonMissing([
            'status' => 2,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_with_constraints_router()
    {
        $reviews = factory(Review::class, 5)->create();
        $review_constraints = $reviews[0]->review;
        $reviews = $reviews->map(function ($reviews) {
            unset($reviews['created_at']);
            unset($reviews['updated_at']);
            return $reviews;
        })->toArray();

        $constraints = '{"review":"' . $review_constraints . '"}';

        $response = $this->json('GET', '/api/reviews/posts?constraints=' . $constraints);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$reviews[0]],
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_with_search_router()
    {
        factory(Review::class, 5)->create();
        $review = factory(Review::class)->create(['review' => 'test_review'])->toArray();
        unset($review['created_at']);
        unset($review['updated_at']);
        $response = $this->json('GET', '/api/reviews/posts?search=test_review');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$review],
        ]);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_with_order_router()
    {
        $reviews = factory(Review::class, 5)->create();
        $reviews = $reviews->map(function ($reviews) {
            unset($reviews['created_at']);
            unset($reviews['updated_at']);
            return $reviews;
        })->toArray();
        $order_by = '{"id":"desc"}';
        $listId = array_column($reviews, 'id');
        array_multisort($listId, SORT_DESC, $reviews);

        $response = $this->json('GET', '/api/reviews/posts?order_by=' . $order_by);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $reviews,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }

    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_from_field_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => '', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_from_field()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'test', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_field_from_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'from' => ''];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_resource_with_from_date_router()
    {
        $reviews = factory(Review::class, 5)->create(['created_at' => '01/08/2021'])->toArray();
        foreach ($reviews as $reviews) {
            unset($reviews['updated_at']);
            unset($reviews['created_at']);
        }
        $data = ['field' => 'created', 'from' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $reviews,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

        $response->assertStatus(200);

    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_field_from()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'from' => '3/8/2021'];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_to_field_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => '', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_to_field()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'test', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_field_to_required()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'to' => ''];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_field_to()
    {
        factory(Review::class, 5)->create();
        $data = ['field' => 'updated', 'to' => '3/8/2021'];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_resource_with_to_date_router()
    {
        $reviews = factory(Review::class, 5)->create()->toArray();
        foreach ($reviews as $reviews) {
            unset($reviews['updated_at']);
            unset($reviews['created_at']);
        }
        $data = ['field' => 'created', 'to' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $reviews,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

        $response->assertStatus(200);

    }
    /**
     * @test
     */
    public function should_not_get_all_paginate_reviews_resource_with_status_router()
    {
        $reviews = factory(Review::class, 5)->create()->toArray();
        $data = ['status' => ''];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $this->assertRequired($response, 'The input status is incorrect');
    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_resource_with_status_router()
    {
        $reviews = factory(Review::class, 5)->create()->toArray();
        factory(Review::class, 5)->create(['status' => 2])->toArray();
        $data = ['status' => 1];
        $response = $this->json('GET', '/api/reviews/posts/1', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 1,
        ]);
        $response->assertJsonMissing([
            'status' => 2,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_resource_with_constraints_router()
    {
        $reviews = factory(Review::class, 5)->create();
        $review_constraints = $reviews[0]->review;
        $reviews = $reviews->map(function ($reviews) {
            unset($reviews['created_at']);
            unset($reviews['updated_at']);
            return $reviews;
        })->toArray();

        $constraints = '{"review":"' . $review_constraints . '"}';

        $response = $this->json('GET', '/api/reviews/posts/1?constraints=' . $constraints);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$reviews[0]],
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_resource_with_search_router()
    {
        factory(Review::class, 5)->create();
        $review = factory(Review::class)->create(['review' => 'test_review'])->toArray();
        unset($review['created_at']);
        unset($review['updated_at']);
        $response = $this->json('GET', '/api/reviews/posts/1?search=test_review');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$review],
        ]);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_get_all_paginate_reviews_resource_with_order_router()
    {
        $reviews = factory(Review::class, 5)->create();
        $reviews = $reviews->map(function ($reviews) {
            unset($reviews['created_at']);
            unset($reviews['updated_at']);
            return $reviews;
        })->toArray();
        $order_by = '{"id":"desc"}';
        $listId = array_column($reviews, 'id');
        array_multisort($listId, SORT_DESC, $reviews);

        $response = $this->json('GET', '/api/reviews/posts/1?order_by=' . $order_by);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $reviews,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_not_create_review_resource_type_required_router()
    {
        $data = factory(Review::class)->make(['resource_type' => ''])->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $this->assertValidator($response, 'resource_type', 'The resource type field is required.');
        $this->assertDatabaseMissing('reviews', $data);
    }
    /**
     * @test
     */
    public function should_not_create_review_resource_id_required_router()
    {
        $data = factory(Review::class)->make(['resource_id' => ''])->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $this->assertValidator($response, 'resource_id', 'The resource id field is required.');
        $this->assertDatabaseMissing('reviews', $data);
    }

    /**
     * @test
     */
    public function should_not_create_review_not_string_router()
    {
        $data = factory(Review::class)->make(['review' => 123])->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $this->assertValidator($response, 'review', 'The review must be a string.');
        $this->assertDatabaseMissing('reviews', $data);
    }
    /**
     * @test
     */
    public function should_not_create_review_rating_min_router()
    {
        $data = factory(Review::class)->make(['rating' => 0])->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $this->assertValidator($response, 'rating', 'The rating must be at least 1.');
        $this->assertDatabaseMissing('reviews', $data);
    }
    /**
     * @test
     */
    public function should_not_create_review_rating_max_router()
    {
        $data = factory(Review::class)->make(['rating' => 6])->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $this->assertValidator($response, 'rating', 'The rating may not be greater than 5.');
        $this->assertDatabaseMissing('reviews', $data);
    }
    /**
     * @test
     */
    public function should_not_create_review_rating_not_number_router()
    {
        $data = factory(Review::class)->make(['rating' => 'a'])->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $this->assertValidator($response, 'rating', 'The rating must be a number.');
        $this->assertDatabaseMissing('reviews', $data);
    }
    /**
     * @test
     */
    public function should_not_create_review_status_required_router()
    {
        $data = factory(Review::class)->make(['status' => null])->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $this->assertValidator($response, 'status', 'The status field is required.');
        $this->assertDatabaseMissing('reviews', $data);
    }
    /**
     * @test
     */
    public function should_create_review_router()
    {
        $data = factory(Review::class)->make()->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $response = $this->json('POST', '/api/reviews/posts', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $data,
        ]);

        $this->assertDatabaseHas('reviews', $data);
    }
}
