<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // print_r($data);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('image') != '')
            $user->image = $request->input('image');
        $user->password = Hash::make($request->input('password'));

        if ($request->input('image') != '' || $request->input('image') != null) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $image->move('uploads/users/', $filename);
                $user->image = $filename;
            }
        }

        $user->save();

        // $user = User::create($request->all());
        return response()->json(["code" => "201", "message" => "Account created successfully", "user" => $user], 201);
    }


    public function loginUsingSocial(Request $request)
    {
        $request->validate([
            'authKey' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('authKey', $request->authKey)->first();

        if (is_null($user)) {
            $user = new User;
            $user->authKey = $request->input('authKey');
            $user->name = $request->input('name');
            if ($request->input('email') != '')
                $user->email = $request->input('email');
            if ($request->input('image') != '')
                $user->image = $request->input('image');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return $user->createToken($request->device_name)->plainTextToken;
        } else {
            return $user->createToken($request->device_name)->plainTextToken;
        }
    }

    public function updateEmail(Request $request)
    {
        $user = User::find($request->id);
        $user->email = $request->email;
        $user->update();

        $updatedUser = User::where('id', $request->id)->withCount(['following', 'followers'])->first();
        return response()->json($updatedUser, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUserInfo($id)
    {
        $user = User::find($id);
        $user->noOfRecipes = DB::table('recipes')->where('userId', '=', $user->id)->count();
        if (is_null($user)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json(["message" => "Record not found!"], 404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

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

        $user->update();
        $updatedUser = User::where('id', $user->id)->withCount(['following', 'followers'])->first();
        return response()->json(["user" => $updatedUser, "message" => "Profile updated successfully"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteImage($id)
    {
        $user = User::where('id', $id)->withCount(['following', 'followers'])->first();
        if ($user->image != null) {
            $oldImagePath = public_path("/uploads/users/$user->image");
            if (File::exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        DB::table('users')->where('id', $id)->update(['image' => NULL]);

        return response($user, 200);
    }

    public function changePassword($id, $oldPassword, $newPassword)
    {
        $user = User::find($id);

        if (Hash::check($oldPassword, $user->password)) {
            $user->update(['password' => Hash::make($newPassword)]);
        } else {
            return response()->json(["message" => "Current password is incorrect"], 404);
        }

        return response()->json(["message" => "Password updated successfully"], 200);
    }

    public function search($name, $id)
    {
        $user = User::where('name', "like", "%" . $name . "%")->where('id', '!=', $id)->get();
        if (is_null($user)) {
            return response()->json(["message" => "No user found!"], 404);
        }
        return response()->json($user, 200);
    }

    public function addUserFollow($id, $followerId)
    {

        $follow = DB::table('user_followers')->where([['userId', '=', $id], ['followerId', '=', $followerId]])->first();

        if (is_null($follow)) {
            DB::table('user_followers')->insert(['userId' =>  $id, 'followerId' => $followerId]);
            $following = User::find($id);
            $follower = User::find($followerId);
            $follower->pushNotification('New Follower!', $following->name . ' started following you', ['follow' => $following]);
        } else {
            DB::table('user_followers')->where([['userId', '=', $id], ['followerId', '=', $followerId]])->delete();
        }
        return response()->json(["following" => true], 201);
    }

    public function getProfileInfo($lang, $id)
    {
        $recipeCount = Recipe::where([['userId', '=', $id], ['status', '=', 2], ['language_code', '=', $lang]])->count();
        $followingCount = DB::table('user_followers')->where('userId', '=', $id)->count();
        $followerCount = DB::table('user_followers')->where('followerId', '=', $id)->count();

        return response()->json(["recipeCount" => $recipeCount, "followingCount" => $followingCount, "followerCount" => $followerCount], 200);
    }

    public function fetchFollowingFollowers($id)
    {
        $followingUsers = DB::table('user_followers')->join('users', 'users.id', '=', 'user_followers.followerId')->where('user_followers.userId', '=', $id)->select('users.*')->get();
        $followerUsers = DB::table('user_followers')->join('users', 'users.id', '=', 'user_followers.userId')->where('followerId', '=', $id)->select('users.*')->get();

        return response()->json(['following' => $followingUsers, 'followers' => $followerUsers], 200);
    }


    public function checkIfUserIsFollowing($id, $followerId)
    {
        $users = DB::table('user_followers')->where(['userId' => $id, 'followerId' => $followerId])->count();

        if ($users == 0) {
            return response()->json(["following" => false], 200);
        } else {
            return response()->json(["following" => true, "message" => "Already Following User"], 200);
        }
    }

    public function setDeviceToken($token)
    {
        $exist = DeviceToken::where('device_token', '=', $token)->first();

        if (is_null($exist)) {
            DeviceToken::insert(['device_token' => $token]);
            return response()->json(["message" => "Token added successfully"], 201);
        } else {
            return response()->json(["message" => "Token already exist"], 201);
        }
    }

    public function forgotPassword(Request $request)
    {
        if (User::where('email', '=', $request->email)->exists()) {
            $credentials = request()->validate(['email' => 'required|email']);
            Password::sendResetLink($credentials);

            return response()->json(["message" => 'Reset password link sent to your email'], 200);
        } else {
            return response()->json(["message" => 'Email not found'], 200);
        }
    }

    public function checkAccountStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 0) {
            return response()->json(["status" => false], 200);
        } else {
            return response()->json(["status" => true], 200);
        }
    }

    public function getUserFavorites(Request $request)
    {
        $recipesIds = json_decode($request->recipeIds, true);
        $allRecipes = Recipe::where([['status', '2'], ['language_code', $request->lang]])->with(['user', 'difficulty', 'cuisine'])->get();

        $recipes = [];
        foreach ($allRecipes as $recipe) {
            if (in_array($recipe->id, $recipesIds))
                $recipes[] = $recipe;
        }

        return response()->json($recipes, 200);
    }
}
