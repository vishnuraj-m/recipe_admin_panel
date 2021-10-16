<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuisine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CuisineController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang)
    {
        $cuisines = Cuisine::where([['status', '=', '1'], ['language_code', '=', $lang]])->get();
        return response()->json($cuisines, 200);
    }

    public function fetchCuisines($lang, $offset)
    {
        $cuisines = Cuisine::where([['status', '=', '1'], ['language_code', '=', $lang]])->paginate($offset);
        return response()->json($cuisines, 200);
    }

}
