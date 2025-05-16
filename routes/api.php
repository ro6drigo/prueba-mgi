<?php

use App\Http\Controllers\EventController;

Route::post('events', [EventController::class, 'store']);
