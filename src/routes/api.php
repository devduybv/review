<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->get('reviews/{resource_type}', 'VCComponent\Laravel\Review\Http\Controllers\Api\Admin\ReviewController@index');
    $api->post('reviews/{resource_type}', 'VCComponent\Laravel\Review\Http\Controllers\Api\Admin\ReviewController@store');
    $api->get('reviews/{resource_type}/{id}', 'VCComponent\Laravel\Review\Http\Controllers\Api\Admin\ReviewController@getItemResource');

});
