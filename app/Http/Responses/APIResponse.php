<?php

namespace App\Http\Responses;

use \Illuminate\Http\JsonResponse;

use Carbon\Carbon;

class APIResponse
{
    public static function makeSuccess($data): JsonResponse
    {
        $respond = [
            'data' => $data,
            'time' => Carbon::now()->toDateTimeString()
        ];

        return response()->json($respond);
    }
}
