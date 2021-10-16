<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Difficulty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validator;


class DifficultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang)
    {
        $difficulties = Difficulty::where('language_code', $lang)->get();
        return response()->json($difficulties, 200);
    }
}
