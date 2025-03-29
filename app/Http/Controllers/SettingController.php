<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display and edit application settings.
     */
    public function index()
    {
        $settings = Setting::orderBy('key')->get();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        $inputs = $request->except('_token', '_method');

        foreach ($inputs as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}