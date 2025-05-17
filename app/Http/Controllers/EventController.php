<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Resources\EventResource;
use App\Services\StoreEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Registrar un evento de contenedor",
     *     tags={"Events"},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreEventRequest")),
     *     @OA\Response(response=201, description="Evento creado exitosamente", @OA\JsonContent(
     *         @OA\Property(property="data", ref="#/components/schemas/EventResource"))
     *     )),
     * )
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = StoreEventService::call(
            $request->validated('containerId'),
            $request->validated('state'),
            Carbon::parse($request->validated('timestamp')),
            $request->validated('source'),
        );

        return (new EventResource($event))->response()->setStatusCode(201);
    }
}
