<?php
namespace VCComponent\Laravel\Review\Repositories;

interface ReviewInterface
{
    public function model();
    public function getEntity();
    public function getReviewTypeFromRequest($request);
    public function applyQueryScope($query, $field, $value);
    public function getStatus($request, $query);
    public function fomatDate($date);
    public function field($request);
    public function getFromDate($request, $query);
    public function getToDate($request, $query);
}
