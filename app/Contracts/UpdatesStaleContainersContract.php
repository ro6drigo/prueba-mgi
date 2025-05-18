<?php

namespace App\Contracts;

interface UpdatesStaleContainersContract
{
    public function updateStaleContainers(): void;
}
