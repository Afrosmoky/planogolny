<?php

use Illuminate\Support\Facades\Route;

Route::get('/gis/ping', function () {
    return ['status' => 'ok'];
});
