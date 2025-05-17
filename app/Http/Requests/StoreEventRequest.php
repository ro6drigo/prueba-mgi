<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreEventRequest",
 *     required={"containerId","state","timestamp","source"},
 *     @OA\Property(property="containerId", type="string", example="ABC123"),
 *     @OA\Property(property="state", type="string", example="operational"),
 *     @OA\Property(property="timestamp", type="string", format="date-time", example="2025-04-10T10:00:00Z"),
 *     @OA\Property(property="source", type="string", example="sensor_x")
 * )
 */
class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'containerId' => 'required|string',
            'state' => 'required|string',
            'timestamp' => 'required|date',
            'source' => 'required|string',
        ];
    }
}
