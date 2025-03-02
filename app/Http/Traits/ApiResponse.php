<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait ApiResponse
{

    /**
     * Response JSON with success
     *
     * @param array $data
     * @param string $successMessage
     * @param int $successCode
     *
     * @return JsonResponse
     */
    public function responseWithSuccess(array $data = [], string $successMessage = 'Request successful', int $successCode = 200): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $successMessage, 'data' => $data], $successCode);
    }

    /**
     * Response JSON with error
     *
     * @param \Throwable $error
     * @param string $errorMessage
     * @param int $errorCode
     *
     * @return JsonResponse
     */
    public function responseWithError(\Throwable $error, string $errorMessage = 'Internal Server Error', int $errorCode = 500): JsonResponse
    {
        Log::error($error->getMessage(), [
            'user'      => Auth::user()->email,
            'message'   => $error->getMessage(),
            'file'      => $error->getFile(),
            'line'      => $error->getLine(),
            'trace'     => $error->getTrace()
        ]);

        return response()->json(['success' => false, 'message' => $errorMessage], $errorCode);

    }
}
