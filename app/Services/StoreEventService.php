<?php

namespace App\Services;

use App\Models\Container;
use App\Models\Event;
use Illuminate\Support\Carbon;

class StoreEventService
{
    public static function call(string $containerId, string $state, Carbon $timestamp, string $source): Event
    {
        $event = Event::create([
            'container_id' => $containerId,
            'state' => $state,
            'timestamp' => $timestamp,
            'source' => $source,
        ]);

        $container = Container::firstOrCreate(['id' => $containerId], ['state' => 'unknown']);

        $container->update([
            'state' => ResolveContainerStateService::call($container),
        ]);

        return $event;
    }
}
