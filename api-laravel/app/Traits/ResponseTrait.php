<?php

namespace App\Traits;

trait ResponseTrait
{
    public function success($data = [], $status_code = 200)
    {   
        return response()->json([
            'success' => true,
            'data' => $data
        ], $status_code);
    }

    public function error(int $status_code, $data = null)
    {   
        return response()->json([
            'success' => false,
            'data' => $data
        ], $status_code);
    }
}