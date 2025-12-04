<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    /**
     * Display the general settings form.
     */
    public function index()
    {
        // $settings = GeneralSetting::first();
        return view('adminlayout.general.index');
    }

    /**
     * Update the general settings.
     */
    // public function update(Request $request)
    // {
    //     $validated = $request->validate([
    //         'currency' => 'nullable|string|max:255',
    //         'currency_symbol' => 'nullable|string|max:10',
    //         'country_code' => 'nullable|string|max:10',
    //         'phone' => 'nullable|string|max:20',
    //         'email' => 'nullable|email|max:255',
    //         'address' => 'nullable|string|max:500',
    //     ]);

    //     $settings = GeneralSetting::first();

    //     if ($settings) {
    //         $settings->update($validated);
    //     } else {
    //         GeneralSetting::create($validated);
    //     }

    //     return redirect()->back()->with('success', 'General settings updated successfully!');
    // }
}
