<?php

namespace App\Http\Controllers;

use App\Services\PersonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonController extends Controller
{

    public function __construct(
        protected PersonService $person_service
    ) {
    }

    /**
     * Search by name
     *
     * @param  mixed  $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'nullable|string',
        ]);

        $result = $this->person_service->getNames(
            $request->only(['name', 'type'])
        );

        return response()->json(
            $result
        );
    }

}
