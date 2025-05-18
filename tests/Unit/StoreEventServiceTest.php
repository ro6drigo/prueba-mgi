<?php

namespace Tests\Unit;

use App\Models\Container;
use App\Models\Event;
use App\Services\StoreEventService;
use Tests\TestCase;

class StoreEventServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Container::truncate();
        Event::truncate();
    }

    public function test_creates_event_and_new_container()
    {
        $now = now();

        StoreEventService::call(
            'new_container',
            'operational',
            $now,
            'sensor_1'
        );

        $this->assertDatabaseHas('events', [
            'container_id' => 'new_container',
            'state' => 'operational',
            'source' => 'sensor_1',
        ]);

        $this->assertDatabaseHas('containers', [
            'id' => 'new_container',
            'state' => 'operational',
        ]);
    }

    public function test_adds_event_and_updates_existing_container_state()
    {
        $container = Container::factory()->create(['state' => 'unknown']);
        $now = now();

        StoreEventService::call(
            $container->id,
            'damaged',
            $now,
            'sensor_1'
        );

        $container->refresh();

        $this->assertDatabaseHas('events', [
            'container_id' => $container->id,
            'state' => 'damaged',
            'source' => 'sensor_1',
        ]);

        $this->assertDatabaseHas('containers', [
            'id' => $container->id,
            'state' => 'damaged',
        ]);
    }
}
