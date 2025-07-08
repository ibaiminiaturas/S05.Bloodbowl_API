<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;


class SkillController extends Controller
{

    public function index()
    {
        $skills = Skill::all();
        return response()->json(['data' => $skills]);
    }
}
