<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class ApiController extends BaseController
{
    /**
     * Success response method.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ], $code);
    }

    /**
     * Error response method.
     *
     * @param string $errorMessage
     * @param array $errorData
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $errorMessage, array $errorData = [], int $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $errorMessage,
        ];

        if (!empty($errorData)) {
            $response['errors'] = $errorData;
        }

        return response()->json($response, $code);
    }

    /**
     * Not found response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendNotFound(string $message = 'Resource not found.'): JsonResponse
    {
        return $this->sendError($message, [], 404);
    }

     /**
     * Unauthorized response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendUnauthorized(string $message = 'Unauthorized.'): JsonResponse
    {
        return $this->sendError($message, [], 401);
    }

    /**
     * Forbidden response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendForbidden(string $message = 'Forbidden.'): JsonResponse
    {
        return $this->sendError($message, [], 403);
    }
}
