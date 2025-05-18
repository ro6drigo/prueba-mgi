<?php

namespace Tests\Feature;

use App\Models\Container;
use App\Models\Event;
use Tests\TestCase;

class ContainerControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Container::truncate();
        Event::truncate();
    }

    public function test_get_containers_returns_list()
    {
        Container::factory()->count(3)->create();

        $response = $this->getJson('/api/containers');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [['id', 'state', 'created_at', 'updated_at']],
        ]);
    }

    public function test_get_container_status_returns_correct_state()
    {
        $container = Container::factory()->create(['id' => 'ABC123', 'state' => 'unknown']);

        Event::factory()->count(3)->create([
            'container_id' => $container->id,
            'state' => 'operational',
            'timestamp' => now(),
            'source' => 'sensor_x',
        ]);

        $response = $this->getJson("/api/containers/$container->id/status");

        $response->assertStatus(200)->assertJson([
            'data' => 'operational',
        ]);
    }

    public function test_get_container_status_returns_404_for_missing()
    {
        $response = $this->getJson('/containers/NOTFOUND/status');

        $response->assertStatus(404);
    }
}
