<?php

namespace App\Services;

use App\Contracts\ResolvesContainerStateContract;
use App\Contracts\UpdatesStaleContainersContract;
use App\Models\Container;

class UpdateStaleContainerStatesService implements UpdatesStaleContainersContract
{
    public function __construct(private ResolvesContainerStateContract $containerStateResolver) {}

    public function updateStaleContainers(): void
    {
        $containers = Container::all();

        foreach ($containers as $container) {
            $has_updates = $container->events()->where('created_at', '>', $container->updated_at)->exists();

            if ($has_updates) {
                $container->update(['state' => $this->containerStateResolver->resolve($container)]);
                $container->touch();
            }
        }
    }
}
