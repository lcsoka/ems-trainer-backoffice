<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    public $response = null;

    public function __construct()
    {
        $this->response = new ApiResponder();
    }

}
