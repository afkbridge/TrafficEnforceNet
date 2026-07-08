<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Violation;

class DashboardController extends Controller
{
    public function index()
    {
        $totalViolations = Violation::count();

        $todayTickets = Violation::whereDate('violation_date', today())->count();

        $pendingCases = Violation::where('status', 'Pending')->count();

        // Temporary count of all users
        $activeEnforcers = User::whereHas('role', function ($query) {
        $query->where('name', 'POSO Enforcer');
        })->count();

        return view('admin.dashboard.index', compact(
            'totalViolations',
            'todayTickets',
            'pendingCases',
            'activeEnforcers'
        ));
    }
}