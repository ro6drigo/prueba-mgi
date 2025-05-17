<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Services\ResolveContainerStateService;
use App\Services\UpdateStaleContainerStatesService;
use Illuminate\Http\JsonResponse;

class ContainerController extends Controller
{
    public function index(): JsonResponse
    {
        UpdateStaleContainerStatesService::call();

        $containers = Container::all(['id', 'state']);

        return response()->json([
            'data' => $containers,
        ]);
    }

    public function status(string $id): JsonResponse
    {
        $container = Container::with('events')->findOrFail($id);

        $container->update(['state' => ResolveContainerStateService::call($container)]);
        $container->touch();

        return response()->json([
            'data' => [
                'container_id' => $container->id,
                'state' => $container->state,
            ],
        ]);
    }
}
