<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PlayerType;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $users = User::role('coach', [], 'api')->get();
        return response()->json(['data' => $users]);
    }


    public function show(User $coach)
    {

    }
}
