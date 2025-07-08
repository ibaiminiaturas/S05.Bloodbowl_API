<?php

namespace App\Http\Controllers;
use App\Models\Roster;
use App\Models\PlayerType;
use Illuminate\Http\Request;

class RosterController extends Controller
{

    public function index()
    {
        $rosters = Roster::all();
        return response()->json(['data' => $rosters]);
    }


    public function show(Roster $roster)
    {
        $rosterData = $roster->playerTypes;
        return response()->json(['data' =>$rosterData]);
    }
}
