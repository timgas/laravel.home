<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data = null, int $status = JsonResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    /**
     * @param $data
     * @param int $status
     * @return JsonResponse
     */
    protected function error($data = null, int $status = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => $data,
        ], $status);
    }

    /**
     * @param null $data
     * @return JsonResponse
     */
    protected function created($data = null): JsonResponse
    {
        return $this->success($data, JsonResponse::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    protected function deleted(): JsonResponse
    {
        return $this->success(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

