<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display system settings page
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $vatPercentage = Setting::get('vat_percentage', 0);

        return view('settings.index', compact('vatPercentage'));
    }

    /**
     * Update system settings
     */
    public function update(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'vat_percentage' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('vat_percentage', $request->vat_percentage, 'number');

        return redirect()->route('settings.index')
            ->with('success', 'VAT percentage updated successfully!');
    }
}
