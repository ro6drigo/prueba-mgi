<?php

use App\Http\Controllers\ContainerController;
use App\Http\Controllers\EventController;

// EventController
Route::post('events', [EventController::class, 'store']);

// ContainerController
Route::get('containers', [ContainerController::class, 'index']);
Route::get('containers/{id}/status', [ContainerController::class, 'status']);
