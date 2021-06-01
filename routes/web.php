<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\VaccinationState;

Route::get('/execute_bot', function () {
    Artisan::call('VaccinationStateBot:start');
});
