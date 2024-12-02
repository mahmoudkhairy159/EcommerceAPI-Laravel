<?php

namespace App\Traits;

trait GeneralTrait
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($message = "", int $statusCode = 400)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'success' => false
        ], $statusCode);
    }


    public function returnSuccessMessage($message = "", int $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'success' => true,
        ], $statusCode);
    }

    public function returnData($data, $message = "", int $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
            'success' => true,
        ], $statusCode);
    }
}
