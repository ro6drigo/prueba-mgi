<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Services\StoreEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = StoreEventService::call(
            $request->validated('containerId'),
            $request->validated('state'),
            Carbon::parse($request->validated('timestamp')),
            $request->validated('source'),
        );

        return response()->json([
            'data' => $event,
        ], 201);
    }
}
