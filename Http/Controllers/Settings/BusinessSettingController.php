<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class BusinessSettingController extends Controller
{
    public function edit()
    {
        return view('settings.business', ['settings' => BusinessSetting::firstOrCreate([])]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'business_name' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
        ]);

        BusinessSetting::firstOrCreate([])->update($data);

        return back()->with('success', 'Saved.');
    }
}
