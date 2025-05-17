<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContainerResource;
use App\Models\Container;
use App\Services\ResolveContainerStateService;
use App\Services\UpdateStaleContainerStatesService;
use Illuminate\Http\JsonResponse;

class ContainerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/containers",
     *     summary="Listar contenedores con su estado verificado",
     *     tags={"Containers"},
     *     @OA\Response(response=200, description="Lista de contenedores", @OA\JsonContent(
     *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ContainerResource"))
     *     )),
     * )
     */
    public function index(): JsonResponse
    {
        UpdateStaleContainerStatesService::call();

        $containers = Container::all();

        return ContainerResource::collection($containers)->response()->setStatusCode(code: 200);
    }

    /**
     * @OA\Get(
     *     path="/api/containers/{id}/status",
     *     summary="Consultar el estado confiable de un contenedor",
     *     tags={"Containers"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID del contenedor", @OA\Schema(type="string", example="ABC123")),
     *     @OA\Response(response=200, description="Contenedor", @OA\JsonContent(
     *         @OA\Property(property="data", type="string", example="damaged")
     *     )),
     * )
     */
    public function status(string $id): JsonResponse
    {
        $container = Container::with('events')->findOrFail($id);

        $container->update(['state' => ResolveContainerStateService::call($container)]);
        $container->touch();

        return response()->json(['data' => $container->state], 200);
    }
}
