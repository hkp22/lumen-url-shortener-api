<?php

namespace App\Http\Controllers;

use App\Link;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function linkResponse(Link $link, $merge = [], $status = 200)
    {
        return response()->json([
            'data' => array_merge([
                'original_url' => $link->original_url,
                'short_url' => $link->shortUrl(),
                'code' => $link->code
            ], $merge)
        ], $status);
    }
}
