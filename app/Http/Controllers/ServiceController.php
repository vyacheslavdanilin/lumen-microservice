<?php

namespace App\Http\Controllers;

use App\Services\UpdateService;
use Exception;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{

    public function __construct(
        protected UpdateService $update_service,
    ) {
    }

    /**
     * State
     *
     * @return JsonResponse
     */
    public function state(): JsonResponse
    {
        $response = $this->update_service->getState();

        return response()->json($response);
    }

    /**
     * Update
     *
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        try {
            $this->update_service->update();
        } catch (Exception $e) {
            // Log ...

            return response()->json([
                'result' => false,
                'info' => 'service unavailable',
                'code' => 503,
            ]);
        }

        return response()->json([
            'result' => true,
            'info' => '',
            'code' => 200,
        ]);
    }
}
