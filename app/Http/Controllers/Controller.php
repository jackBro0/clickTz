<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($response)
    {
        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    public function responseFail($code, $error = null)
    {
        return response()->json([
            'success' => false,
            'error_code' => $code,
            'message' => $error
        ], $code);
    }

    public function responseDelete($message = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
