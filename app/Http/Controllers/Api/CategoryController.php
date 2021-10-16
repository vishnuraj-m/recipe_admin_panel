<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validator;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang)
    {
        $categories = Category::where([['status', '=', '1'], ['language_code', '=', $lang]])->get();
        return response()->json($categories, 200);
    }


    public function fetchCategories($lang, $offset)
    {
        $categories = Category::where([['status', '=', '1'], ['language_code', '=', $lang]])->paginate($offset);
        return response()->json($categories, 200);
    }
}
