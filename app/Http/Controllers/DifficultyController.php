<?php

namespace App\Http\Controllers;

use App\Models\Difficulty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DifficultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($condition)
    {
        switch ($condition) {
            case 'all':
                $difficulties = Difficulty::get();
                break;
            default:
                $difficulties = Difficulty::where('language_code', $condition)
                    ->get();
                break;
        }

        $allDifficultiesCount = Difficulty::count();
        $difficultyLangs = Difficulty::select('language_code', DB::raw('count(*) as total'))
            ->groupBy('language_code')
            ->get();

        return view('difficulties.difficulties-index', compact('difficulties', 'allDifficultiesCount', 'difficultyLangs'));
    }

    public function getDifficulties($lang)
    {
        $data['data'] = Difficulty::where('language_code', $lang)
            ->select('id', 'difficulty')
            ->get();


        return response()->json($data);
    }

    public function difficultyedit(Request $request, $id)
    {
        $difficulties = Difficulty::findOrFail($id);
        $available_locales = config('app.locales');
        return view('difficulties.difficulty-edit', compact('difficulties', 'available_locales'));
    }

    public function difficultyupdate(Request $request, $id)
    {
        $difficulty = Difficulty::find($id);
        $difficulty->language_code = $request->input('language_code');
        $difficulty->difficulty = $request->input('difficulty');
        $difficulty->save();

        return redirect('/difficulties/all')->with('status', 'Difficulty updated successfully');
    }

    public function difficultydelete($id)
    {
        $difficulty = Difficulty::findOrFail($id);
        $difficulty->delete();

        return redirect('/difficulties/all')->with('status', 'Difficulty deleted successfully');
    }

    public function difficultyadd(Request $request)
    {
        $difficulty = new Difficulty;
        $difficulty->language_code = $request->input('language_code');
        $difficulty->difficulty = $request->input('difficulty');
        $difficulty->save();

        return redirect('/difficulties/all')->with('status', 'Difficulty added successfully');
    }
}
