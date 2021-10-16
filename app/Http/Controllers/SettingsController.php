<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->get();
        return view('settings.settings', compact('settings'));
    }

    public function settingsupdate(Request $request)
    {
        $exists = DB::table('settings')->where('id', 1)->first();

        if ($exists) {
            DB::table('settings')->update(
                [
                    'fcm_key' => $request->input('fcm_key'),
                    'privacy_policy' => $request->input('privacy_policy'),
                    'terms_and_conditions' => $request->input('terms_and_conditions'),
                    'auto_approve' => $request->has('auto_approve'),
                ]
            );
        } else {
            DB::table('settings')->insert(
                [
                    'fcm_key' => $request->input('fcm_key'),
                    'privacy_policy' => $request->input('privacy_policy'),
                    'terms_and_conditions' => $request->input('terms_and_conditions'),
                    'auto_approve' => $request->has('auto_approve'),
                ]
            );
        }

        return redirect('/settings')->with('status', 'Settings updated successfully');
    }
}
