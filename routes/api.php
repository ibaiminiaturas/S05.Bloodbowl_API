<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\TeamController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);


// Rutas protegidas por autenticación
Route::middleware(['auth:api', 'token.revoked', 'role:admin'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/coaches', [CoachController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);


});
// Rutas protegidas por autenticación
Route::middleware(['auth:api', 'token.revoked', 'role:admin|coach'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/skills', [SkillController::class, 'index']);
    Route::get('/rosters', [RosterController::class, 'index']);
    Route::get('/rosters/{roster}', [RosterController::class, 'show']);
    Route::get('/coaches/{coach}', [CoachController::class, 'show']);
});
