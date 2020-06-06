<?php

namespace App\Handler;

use Throwable;

use function json_encode;

class ErrorHandler
{
    /**
     * Handle errors in app. Emits answer
     *
     * @param \Throwable $e
     *
     * @return void
     */
    public function exceptionHandler(Throwable $e): void
    {
        if ($e->getCode() === 404) {
            header("HTTP/1.0 404 Not Found");
        } else {
            header("Content-Type: application/json");

            echo json_encode([
                "code" => $e->getCode(),
                "message" => $e->getMessage(),
            ]);
        }
    }
}
