<?php

namespace App\Services;

use App\Models\Container;

class UpdateStaleContainerStatesService
{
    public static function call(): void
    {
        $containers = Container::all();

        foreach ($containers as $container) {
            $has_updates = $container->events()->where('created_at', '>', $container->updated_at)->exists();

            if ($has_updates) {
                $container->update(['state' => ResolveContainerStateService::call($container)]);
                $container->touch();
            }
        }
    }
}
