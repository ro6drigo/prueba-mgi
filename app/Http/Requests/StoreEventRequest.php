<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
