<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PlayerType;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'coach')->where('guard_name', 'api');
        })->get();

        $users = User::role('coach')->get();

        return response()->json(['data' => $users]);
    }


    public function show(User $coach)
    {
        if ($coach->hasRole('coach')) {
            return response()->json(['data' => $coach]);
        } else {
            return response()->json(404);
        }
    }
}
