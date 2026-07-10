<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class InvoiceSettingController extends Controller
{
    public function edit()
    {
        return view('settings.invoice', ['settings' => BusinessSetting::firstOrCreate([])]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'receipt_footer_line1' => ['required', 'string'],
            'receipt_footer_line2' => ['nullable', 'string'],
        ]);

        BusinessSetting::firstOrCreate([])->update($data);

        return back()->with('success', 'Saved.');
    }
}
