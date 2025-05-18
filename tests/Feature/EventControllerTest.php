<?php

namespace Tests\Feature;

use App\Models\Container;
use App\Models\Event;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Container::truncate();
        Event::truncate();
    }

    public function test_post_events_creates_event_and_creates_container()
    {
        $payload = [
            'containerId' => 'ABC123',
            'state' => 'operational',
            'timestamp' => now()->toIso8601String(),
            'source' => 'sensor_x',
        ];

        $response = $this->postJson('/api/events', $payload);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => ['id', 'container_id', 'state', 'timestamp', 'source', 'created_at', 'updated_at'],
        ]);

        $this->assertDatabaseHas('events', [
            'container_id' => 'ABC123',
            'state' => 'operational',
            'source' => 'sensor_x',
        ]);

        $this->assertDatabaseHas('containers', [
            'id' => 'ABC123',
            'state' => 'operational',
        ]);
    }

    public function test_post_events_fails_with_invalid_data()
    {
        $payload = [
            'containerId' => '',
            'state' => '',
            'timestamp' => 'not-a-date',
            'source' => '',
        ];

        $response = $this->postJson('/api/events', $payload);

        $response->assertStatus(422)->assertJsonValidationErrors(['containerId', 'state', 'timestamp', 'source']);
    }
}
