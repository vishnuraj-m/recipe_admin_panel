<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index(string $condition)
    {
        switch ($condition) {
            case 'all':
                $categories = Category::orderBy('created_at', 'desc')
                    ->get();
                break;
            default:
                $categories = Category::where('language_code', $condition)
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
        }

        $allCategoriesCount = Category::count();
        $categoryLangs = Category::select('language_code', DB::raw('count(*) as total'))
            ->groupBy('language_code')
            ->get();

        return view('categories.categories-index', compact('categories', 'allCategoriesCount', 'categoryLangs'));
    }

    public function getCategories($lang)
    {
        $data['data'] = Category::where('language_code', $lang)
            ->select('id', 'name')
            ->get();


        return response()->json($data);
    }


    public function categoryedit(Request $request, $id)
    {
        $categories = Category::findOrFail($id);
        $available_locales=config('app.locales');
        return view('categories.category-edit', compact('categories', 'available_locales'));
    }

    public function categoryupdate(Request $request, $id)
    {
        $categories = Category::find($id);
        $categories->language_code = $request->input('language_code');
        $categories->name = $request->input('name');
        $categories->status = $request->input('status');

        if ($request->hasFile('image')) {
            if ($categories->image != null) {
                $oldImagePath = public_path("/uploads/categories/$categories->image");
                unlink($oldImagePath);
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/categories/', $filename);
            $categories->image = $filename;
        }

        $categories->save();


        return redirect('/categories/all')->with('status', 'Category updated successfully');
    }

    public function categorydelete($id)
    {
        $categories = Category::findOrFail($id);
        if ($categories->image != null) {
            $oldImagePath = public_path("/uploads/categories/$categories->image");
            unlink($oldImagePath);
        }
        $categories->delete();

        return redirect('/categories/all')->with('status', 'Category deleted successfully');
    }

    public function categoryadd(Request $request)
    {
        $category = new Category;
        $category->language_code = $request->input('language_code');
        $category->name = $request->input('name');
        $category->status = $request->input('status');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/categories/', $filename);
            $category->image = $filename;
        } else {
            $category->image = '';
        }

        $category->save();

        return redirect('/categories/all')->with('status', 'Category added successfully');
    }
}
