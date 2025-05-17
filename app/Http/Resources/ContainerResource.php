<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ContainerResource",
 *     @OA\Property(property="id", type="string", example="ABC123"),
 *     @OA\Property(property="state", type="string", example="operational"),
 *     @OA\Property(property="created_at", type="string", example="2025-05-17T22:31:37.729000Z"),
 *     @OA\Property(property="updated_at", type="string", example="2025-05-17T22:31:37.729000Z"),
 * )
 */
class ContainerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
