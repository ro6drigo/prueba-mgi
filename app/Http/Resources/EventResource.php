<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="EventResource",
 *     @OA\Property(property="id", type="string", example="68290e490fed36e7a004ac23"),
 *     @OA\Property(property="container_id", type="string", example="ABC123"),
 *     @OA\Property(property="state", type="string", example="operational"),
 *     @OA\Property(property="timestamp", type="string", example="2025-04-10T10:00:00.000000Z"),
 *     @OA\Property(property="source", type="string", example="sensor_x"),
 *     @OA\Property(property="created_at", type="string", example="2025-05-17T22:31:37.729000Z"),
 *     @OA\Property(property="updated_at", type="string", example="2025-05-17T22:31:37.729000Z"),
 * )
 */
class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'container_id' => $this->container_id,
            'state' => $this->state,
            'timestamp' => $this->timestamp,
            'source' => $this->source,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
