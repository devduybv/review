<?php
namespace VCComponent\Laravel\Review\Repositories;

use Exception;
use Illuminate\Support\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Review\Entities\Review;
use VCComponent\Laravel\Review\Repositories\ReviewInterface;

class ReviewReponsitory extends BaseRepository implements ReviewInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Review::class;
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function getReviewTypeFromRequest($request)
    {
        $path_items = collect(explode('/', $request->path()));
        if ($path_items->count() === 3) {
            $type = $path_items->last();
        } elseif ($path_items->count() === 4) {
            $type = $path_items[2];
        }
        return $type;
    }
    public function applyQueryScope($query, $field, $value)
    {
        $query = $query->where($field, $value);
        return $query;
    }
    public function getStatus($request, $query)
    {
        if ($request->has('status')) {
            $pattern = '/^\d$/';

            if (!preg_match($pattern, $request->status)) {
                throw new Exception('The input status is incorrect');
            }

            $query = $query->where('status', $request->status);
        }
        return $query;
    }

    public function fomatDate($date)
    {

        $fomatDate = Carbon::createFromFormat('Y-m-d', $date);

        return $fomatDate;
    }

    public function field($request)
    {
        if ($request->has('field')) {
            if ($request->field === 'updated') {
                $field = 'updated_at';
            } elseif ($request->field === 'published') {
                $field = 'published_date';
            } elseif ($request->field === 'created') {
                $field = 'created_at';
            }
            return $field;
        } else {
            throw new Exception('field requied');
        }
    }

    public function getFromDate($request, $query)
    {
        if ($request->has('from')) {

            $field = $this->field($request);
            $form_date = $this->fomatDate($request->from);
            $query = $query->whereDate($field, '>=', $form_date);
        }
        return $query;
    }

    public function getToDate($request, $query)
    {
        if ($request->has('to')) {
            $field = $this->field($request);
            $to_date = $this->fomatDate($request->to);
            $query = $query->whereDate($field, '<=', $to_date);
        }
        return $query;
    }
}
