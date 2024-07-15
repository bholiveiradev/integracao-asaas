<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;

trait ApiResponse
{
    public function responseWithSuccess($data = [], $successMessage = 'Request successful', $successCode = 200)
    {
        return response()->json(['success' => true, 'message' => $successMessage, 'data' => $data], $successCode);
    }

    public function responseWithError(\Throwable $error, $errorMessage = 'Internal Server Error', $errorCode = 500)
    {
        Log::error($error->getMessage(), [
            'user'      => auth()->user()->email,
            'message'   => $error->getMessage(),
            'file'      => $error->getFile(),
            'line'      => $error->getLine(),
            'trace'     => $error->getTrace()
        ]);

        return response()->json(['success' => false, 'message' => $errorMessage], $errorCode);

    }
}
