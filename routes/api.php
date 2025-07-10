<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamPlayerController;
use App\Http\Controllers\SimulateMatchController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);


Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running!'
    ]);
});

// Rutas protegidas por autenticación
Route::middleware(['auth:api', 'token.revoked', 'role:admin'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/coaches', [CoachController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/matches/simulate', [SimulateMatchController::class, 'post']);

});
// Rutas protegidas por autenticación
Route::middleware(['auth:api', 'token.revoked', 'role:admin|coach'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/skills', [SkillController::class, 'index']);
    Route::get('/rosters', [RosterController::class, 'index']);
    Route::get('/rosters/{roster}', [RosterController::class, 'show']);
    Route::get('/coaches/{coach}', [CoachController::class, 'show']);
    Route::put('/teams/{team}', [TeamController::class, 'update']);
    Route::get('/teams/{team}', [TeamController::class, 'show']);
    Route::post('/teams/{team}/players', [TeamPlayerController::class, 'store']);
    Route::put('/players/{teamPlayer}', [TeamPlayerController::class, 'update']);
    Route::delete('/players/{teamPlayer}', [TeamPlayerController::class, 'delete']);

});
