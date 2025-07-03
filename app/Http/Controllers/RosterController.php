<?php

namespace App\Http\Controllers;
use App\Models\Roster;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rosters = Roster::all();
        return response()->json(['data' => $rosters]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Roster $roster)
    {
        return response()->json($roster);
    }
}
