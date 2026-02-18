<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * ProfileController - Handles user profile management
 */
class ProfileController extends Controller
{
    /**
     * Display user profile page
     */
    public function show()
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

}
