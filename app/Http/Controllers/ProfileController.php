<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        // auth()->user()->update($request->all());
        $id = Auth::id();

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('image')) {
            if ($user->image != null) {
                $oldImagePath = public_path("/uploads/users/$user->image");
                if (File::exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/users/', $filename);
            $user->image = $filename;
        }

        $user->save();

        return back()->withStatus(__('Profile successfully updated.'));
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::id();

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('image')) {
            if ($user->image != null) {
                $oldImagePath = public_path("/uploads/users/$user->image");
                if (File::exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/users/', $filename);
            $user->image = $filename;
        }

        $user->save();

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withStatusPassword(__('Password successfully updated.'));
    }
}
