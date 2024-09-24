<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PesananAPIController;
use App\Http\Controllers\Api\MenuAPIController;

Route::get('/api/getmenus', [MenuAPIController::class, 'index']);
Route::post('/api/newpesanan', [PesananAPIController::class, 'store']);
