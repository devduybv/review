<?php

namespace VCComponent\Laravel\Review\Http\Controllers\FrontEnd;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }
}
