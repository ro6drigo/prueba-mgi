<?php

namespace App\Services;

use App\Models\Container;

class ResolveContainerStateService
{
    private const int ACCEPTABLE_HOURS = 1;

    private const int QUORUM_ACCEPTABLE_MINUTES = 30;

    private const int QUORUM_MINIMUM = 3;

    public static function call(Container $container): string
    {
        $acceptable_events = $container->events()
            ->where('timestamp', '>=', now()->subHours(static::ACCEPTABLE_HOURS))
            ->orderBy('timestamp')
            ->get();

        if ($acceptable_events->count() === 0) return 'unknown';

        $acceptable_quorum_events = $container->events()
            ->where('timestamp', '>=', now()->subMinutes(static::QUORUM_ACCEPTABLE_MINUTES))
            ->orderBy('timestamp', 'desc')
            ->get();

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

        if ($results['quorum'] >= static::QUORUM_MINIMUM) return $results['state'];

        return $acceptable_events->last()->state;
    }
}
