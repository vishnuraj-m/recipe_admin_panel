<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Status $statuses)
    {
        return view('recipes.recipe-status-index', ['statuses' => $statuses->get()]);
    }

    public function statusedit(Request $request, $id)
    {
        $statuses = Status::findOrFail($id);
        return view('recipes.recipe-status-edit')->with('statuses', $statuses);
    }

    public function statusupdate(Request $request, $id)
    {
        $statuses = Status::find($id);
        $statuses->name = $request->input('name');
        $statuses->color = $request->input('color');
        $statuses->update();

        return redirect('/statuses')->with('status', 'Status updated successfully');
    }

    public function statusdelete($id)
    {
        $statuses = Status::findOrFail($id);
        $statuses->delete();

        return redirect('/statuses')->with('status', 'Status deleted successfully');
    }

    public function statusadd(Request $request)
    {
        $status = new Status;

        $status->name = $request->input('name');
        $status->color = $request->input('color');

        $status->save();

        return redirect('/statuses')->with('status', 'Status added successfully');
    }
}
