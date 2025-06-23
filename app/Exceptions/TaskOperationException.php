<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskOperationException extends Exception
{
    public function __construct(
        string $message = 'Task operation failed. Please try again.',
        int $code = 500,
        \Throwable $previous = null
    )
    {
       parent::__construct($message, $code, $previous);
    }

    public function report(): void
    {
        Log::error($this->getPrevious(), [
            'exception' => $this,
            'trace' => $this->getTraceAsString()
        ]);
    }

    public function render(Request $request): JsonResponse|bool
    {
        if (!$request->is('api/*')) {
            return false;
        }

        return response()->json([
            'message' => $this->getMessage()
        ], $this->getCode());
    }
}
