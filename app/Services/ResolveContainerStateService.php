<?php

namespace App\Services;

use App\Models\Container;
use App\Models\Event;

class ResolveContainerStateService
{
    private const int ACCEPTABLE_HOURS = 1;

    private const int QUORUM_ACCEPTABLE_MINUTES = 30;

    private const int QUORUM_MINIMUM = 3;

    public static function call(Container $container): string
    {
        $events = $container->events()
            ->where('timestamp', '>=', now()->subHours(static::ACCEPTABLE_HOURS))
            ->orderBy('timestamp', 'desc')
            ->get();

        if ($events->isEmpty()) return 'unknown';

        $acceptable_quorum_events = $events->filter(fn (Event $event) => $event->timestamp >= now()->subMinutes(static::QUORUM_ACCEPTABLE_MINUTES));

        $results = ['state' => null, 'quorum' => 0, 'sources' => []];
        foreach ($acceptable_quorum_events as $event) {
            if ($results['quorum'] >= static::QUORUM_MINIMUM) break;

            if ($results['state'] === $event->state) {
                if (! in_array($event->source, $results['sources'])) {
                    $results['quorum']++;
                    $results['sources'][] = $event->source;
                }
            } else {
                $results['quorum'] = 1;
                $results['sources'] = [$event->source];
            }

            $results['state'] = $event->state;
        }

        return $results['quorum'] >= static::QUORUM_MINIMUM
            ? $results['state']
            : $events->first()->state;
    }
}
