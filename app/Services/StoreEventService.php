<?php

namespace App\Services;

use App\Contracts\ResolvesContainerStateContract;
use App\Contracts\StoresEventsContract;
use App\Models\Container;
use App\Models\Event;
use Carbon\CarbonInterface;

class StoreEventService implements StoresEventsContract
{
    public function __construct(private ResolvesContainerStateContract $containerStateResolver) {}

    public function store(string $containerId, string $state, CarbonInterface $timestamp, string $source): Event
    {
        $event = Event::create([
            'container_id' => $containerId,
            'state' => $state,
            'timestamp' => $timestamp,
            'source' => $source,
        ]);

        $container = Container::firstOrCreate(['id' => $containerId], ['state' => 'unknown']);

        $container->update([
            'state' => $this->containerStateResolver->resolve($container),
        ]);

        return $event;
    }
}
