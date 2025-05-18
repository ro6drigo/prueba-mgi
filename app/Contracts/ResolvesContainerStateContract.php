<?php

namespace App\Contracts;

use App\Models\Container;

interface ResolvesContainerStateContract
{
    public function resolve(Container $container): string;
}
