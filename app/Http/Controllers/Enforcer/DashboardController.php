<?php

namespace App\Http\Controllers\Enforcer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('enforcer.dashboard');
    }
}