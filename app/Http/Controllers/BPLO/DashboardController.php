<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('bplo.dashboard');
    }
}