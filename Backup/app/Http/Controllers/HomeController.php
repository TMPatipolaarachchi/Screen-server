<?php

namespace App\Http\Controllers;

/**
 * HomeController - Handles home page display
 */
class HomeController extends Controller
{
    /**
     * Display the home dashboard
     */
    public function index()
    {
        return view('home');
    }
}
