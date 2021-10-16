<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = DB::table('settings')->find(1);
        return response()->json($settings, 200);
    }

    public function fetchLanguages() {
        $available_locales = config('app.locales');

        $languages = array();
        $i = 0;
        foreach ($available_locales as $arrKey => $arrData) {
            $languages[$i]['id'] = $i + 1;
            $languages[$i]['code'] = $arrKey;
            $languages[$i]['name'] = $arrData;
            $i++;
        }

        return response()->json($languages, 200);
    }
}
