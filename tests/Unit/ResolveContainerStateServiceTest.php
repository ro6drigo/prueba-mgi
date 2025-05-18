<?php

namespace Tests\Unit;

use App\Models\Container;
use App\Models\Event;
use App\Services\ResolveContainerStateService;
use Tests\TestCase;

class ResolveContainerStateServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Container::truncate();
        Event::truncate();
    }

    public function test_returns_unknown_if_no_recent_events()
    {
        $container = Container::factory()->create();

        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'operational',
            'timestamp' => now()->subHours(2), // Fuera de la ventana de 1 hora
        ]);

        $result = ResolveContainerStateService::call($container);

        $this->assertEquals('unknown', $result);
    }

    public function test_returns_state_when_quorum_is_reached()
    {
        $container = Container::factory()->create();
        $now = now();

        foreach (['sensor_1', 'sensor_2', 'sensor_3'] as $source) {
            Event::factory()->create([
                'container_id' => $container->id,
                'state' => 'damaged',
                'source' => $source,
                'timestamp' => $now->copy()->subMinutes(5),
            ]);
        }

        $result = ResolveContainerStateService::call($container);

        $this->assertEquals('damaged', $result);
    }

    public function test_returns_latest_state_when_quorum_not_reached()
    {
        $container = Container::factory()->create();
        $now = now();

        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'operational',
            'source' => 'sensor_1',
            'timestamp' => $now->copy()->subMinutes(10),
        ]);

        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'damaged',
            'source' => 'sensor_2',
            'timestamp' => $now->copy()->subMinutes(8),
        ]);

        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'operational',
            'source' => 'sensor_3',
            'timestamp' => $now->copy()->subMinutes(6),
        ]);

        // No hay quórum, así que toma el evento más reciente dentro de la ventana de 1 hora
        $result = ResolveContainerStateService::call($container);

        $this->assertEquals('operational', $result);
    }

    public function test_ignores_duplicate_sources_in_quorum()
    {
        $container = Container::factory()->create();
        $now = now();

        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'operational',
            'source' => 'sensor_x',
            'timestamp' => $now->copy()->subMinutes(5),
        ]);
        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'operational',
            'source' => 'sensor_x', // misma fuente
            'timestamp' => $now->copy()->subMinutes(4),
        ]);
        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'operational',
            'source' => 'sensor_y',
            'timestamp' => $now->copy()->subMinutes(3),
        ]);
        Event::factory()->create([
            'container_id' => $container->id,
            'state' => 'damaged',
            'source' => 'sensor_z',
            'timestamp' => $now->copy()->subMinutes(2),
        ]);

        $result = ResolveContainerStateService::call($container);

        // Solo se cuentan fuentes distintas, se alcanza quórum
        $this->assertEquals('damaged', $result);
    }
}
