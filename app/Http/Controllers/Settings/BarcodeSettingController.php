<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class BarcodeSettingController extends Controller
{
    public function edit()
    {
        return view('settings.barcode', ['settings' => BusinessSetting::firstOrCreate([])]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(['barcode_prefix' => ['required', 'string', 'max:10']]);
        $data['barcode_prefix'] = strtoupper($data['barcode_prefix']);

        BusinessSetting::firstOrCreate([])->update($data);

        return back()->with('success', 'Saved.');
    }
}
