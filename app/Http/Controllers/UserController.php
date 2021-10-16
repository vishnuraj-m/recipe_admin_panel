<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $users)
    {
        $id = Auth::id();
        $users = $users::with('recipes')->where('id', '!=', $id)->get();
        return view('users.users-index', ['users' => $users]);
    }

    public function useredit(Request $request, $id)
    {
        $users = User::findOrFail($id);
        return view('users.user-edit')->with('users', $users);
    }

    public function userupdate(Request $request, $id)
    {
        $users = User::find($id);
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->usertype = $request->input('usertype');
        $users->status = $request->input('status');
        
        if ($request->hasFile('image')) {
            if ($users->image != null) {
                $oldImagePath = public_path("/uploads/users/$users->image");
                if (File::exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/users/', $filename);
            $users->image = $filename;
        }

        $users->save();

        return redirect('/users')->with('status', 'User information updated');
    }

    public function userdelete($id)
    {
        $users = User::findOrFail($id);
        if ($users->image !== null) {
            $oldImagePath = public_path("/uploads/users/$users->image");
            if (File::exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $users->delete();

        return redirect('/users')->with('status', 'User deleted successfully');
    }

    public function useradd(Request $request)
    {
        $user = new User;

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->usertype = $request->input('usertype');
        $user->password = Hash::make($request->input('password'));
        $user->status = $request->input('status');

        if ($request->image != null) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $image->move('uploads/users/', $filename);
                $user->image = $filename;
            } else {
                return $request;
                $user->image = '';
            }
        }

        $user->save();

        return redirect('/users')->with('status', 'User added successfully');
    }

    public function addDeviceToken($token) {
        $deviceToken = new DeviceToken;
        $deviceToken->device_token = $token;
        $deviceToken->save();

        return response()->json(["message" => "Device Token added successfully!"], 201);
    }
}
