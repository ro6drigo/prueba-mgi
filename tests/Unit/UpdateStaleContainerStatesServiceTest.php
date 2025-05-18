<?php

namespace Tests\Unit;

use App\Contracts\ResolvesContainerStateContract;
use App\Models\Container;
use App\Models\Event;
use App\Services\UpdateStaleContainerStatesService;
use Tests\TestCase;

class UpdateStaleContainerStatesServiceTest extends TestCase
{
    protected UpdateStaleContainerStatesService $staleContainersUpdater;

    protected function setUp(): void
    {
        parent::setUp();

        Container::truncate();
        Event::truncate();

        $this->staleContainersUpdater = new UpdateStaleContainerStatesService(app(ResolvesContainerStateContract::class));
    }

    public function test_updates_containers_with_newer_events()
    {
        $now = now();

        $container = Container::factory()->create([
            'state' => 'operational',
            'updated_at' => $now->copy()->subMinutes(10),
        ]);

        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'damaged',
            'timestamp' => $now,
            'created_at' => $now,
        ]);

        $this->staleContainersUpdater->updateStaleContainers();

        $container->refresh();

        $this->assertEquals('damaged', $container->state);
    }

    public function test_not_update_if_no_new_events()
    {
        $now = now();

        $container = Container::factory()->create([
            'state' => 'operational',
            'updated_at' => $now,
        ]);

        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'damaged',
            'timestamp' => $now->copy()->subMinutes(20),
            'created_at' => $now->copy()->subMinutes(20),
        ]);

        $this->staleContainersUpdater->updateStaleContainers();

        $container->refresh();

        $this->assertEquals('operational', $container->state);
    }

    public function test_service_runs_with_no_containers()
    {
        $this->expectNotToPerformAssertions();

        $this->staleContainersUpdater->updateStaleContainers();
    }
}
