<?php

namespace App\Http\Controllers\Enforcer;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get the logged-in enforcer profile
        $enforcer = $user->enforcer;

        // Today's recorded violations
        $todayViolations = Violation::where('user_id', $user->id)
            ->whereDate('violation_date', Carbon::today())
            ->count();

        // Total recorded violations
        $totalViolations = Violation::where('user_id', $user->id)
            ->count();

        // This month's recorded violations
        $monthlyViolations = Violation::where('user_id', $user->id)
            ->whereYear('violation_date', Carbon::now()->year)
            ->whereMonth('violation_date', Carbon::now()->month)
            ->count();

        // Latest 5 recorded violations
        $recentViolations = Violation::with([
                'driver',
                'violationType'
            ])
            ->where('user_id', $user->id)
            ->latest('violation_date')
            ->take(5)
            ->get();

        return view('enforcer.dashboard', compact(
            'enforcer',
            'todayViolations',
            'totalViolations',
            'monthlyViolations',
            'recentViolations'
        ));
    }
}