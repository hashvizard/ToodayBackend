<?php

namespace App\Traits;
use Symfony\Component\HttpFoundation\Response;
use Request;

trait ApiResponse {

	public function successApiResponse($message, $data = null) {
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $data,
            ], Response::HTTP_OK);
	}

    public function successApiPostResponse($message, $data = null) {
        return response()->json([
            'status' => true,
            'message' => $message,
            'posts' => $data,
        ], Response::HTTP_OK);
}

	public function errorApiResponse($message) {
		return response()->json([
			'status' => false,
			'message' => $message,
		], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

     /**
     * Response with status code 422.
     *
     * @param  array  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function unprocessableApiResponse(string $message)
    {
        return response()->json([
			'status' => false,
			'message' => $message,
		], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
