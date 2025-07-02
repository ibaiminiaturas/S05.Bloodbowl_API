<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SkillsController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);


// Rutas protegidas por autenticación
Route::middleware(['auth:api', 'token.revoked', 'role:admin'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });




});
// Rutas protegidas por autenticación
Route::middleware(['auth:api', 'token.revoked', 'role:admin|coach'])->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/skills',[SkillsController::class, 'index']);
});
