<?php

namespace App\Contracts;

use App\Models\Event;
use Carbon\CarbonInterface;

interface StoresEventsContract
{
    public function store(string $containerId, string $state, CarbonInterface $timestamp, string $source): Event;
}
