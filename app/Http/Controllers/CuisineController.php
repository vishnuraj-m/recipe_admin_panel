<?php

namespace App\Http\Controllers;

use App\Models\Cuisine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CuisineController extends Controller
{
    public function index($condition)
    {
        switch ($condition) {
            case 'all':
                $cuisines = Cuisine::orderBy('created_at', 'desc')
                    ->get();
                break;
            default:
                $cuisines = Cuisine::where('language_code', $condition)
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
        }

        $allCuisinesCount = Cuisine::count();
        $cuisineLangs = Cuisine::select('language_code', DB::raw('count(*) as total'))
            ->groupBy('language_code')
            ->get();

        return view('cuisines.cuisines-index', compact('cuisines', 'allCuisinesCount', 'cuisineLangs'));
    }

    public function getCuisines($lang)
    {
        $data['data'] = Cuisine::where('language_code', $lang)
            ->select('id', 'name')
            ->get();


        return response()->json($data);
    }

    public function cuisineedit(Request $request, $id)
    {
        $cuisines = Cuisine::findOrFail($id);
        $available_locales=config('app.locales');
        return view('cuisines.cuisine-edit', compact('cuisines', 'available_locales'));
    }

    public function cuisineupdate(Request $request, $id)
    {
        $cuisines = Cuisine::find($id);
        $cuisines->language_code = $request->input('language_code');
        $cuisines->name = $request->input('name');
        $cuisines->status = $request->input('status');

        if ($request->hasFile('image')) {
            if ($cuisines->image != null) {
                $oldImagePath = public_path("/uploads/cuisines/$cuisines->image");
                unlink($oldImagePath);
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/cuisines/', $filename);
            $cuisines->image = $filename;
        }

        $cuisines->save();

        return redirect('/cuisines/all')->with('status', 'Cuisine updated successfully');
    }

    public function cuisinedelete($id)
    {
        $cuisines = Cuisine::findOrFail($id);
        if ($cuisines->image != null) {
            $oldImagePath = public_path("/uploads/cuisines/$cuisines->image");
            unlink($oldImagePath);
        }
        $cuisines->delete();

        return redirect('/cuisines/all')->with('status', 'Cuisine deleted successfully');
    }

    public function cuisineadd(Request $request)
    {
        $cuisine = new Cuisine;
        $cuisine->language_code = $request->input('language_code');
        $cuisine->name = $request->input('name');
        $cuisine->status = $request->input('status');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/cuisines/', $filename);
            $cuisine->image = $filename;
        } else {
            $cuisine->image = '';
        }


        $cuisine->save();

        return redirect('/cuisines/all')->with('status', 'Cuisine added successfully');
    }
}
