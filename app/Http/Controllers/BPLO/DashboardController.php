<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use App\Models\Violation;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH COUNTS
        |--------------------------------------------------------------------------
        */

        $totalViolations = Violation::whereYear(
            'violation_date',
            $now->year
        )
        ->whereMonth(
            'violation_date',
            $now->month
        )
        ->count();

        $pendingViolations = Violation::whereYear(
            'violation_date',
            $now->year
        )
        ->whereMonth(
            'violation_date',
            $now->month
        )
        ->where('status', 'Pending')
        ->count();

        $reviewedViolations = Violation::whereYear(
            'violation_date',
            $now->year
        )
        ->whereMonth(
            'violation_date',
            $now->month
        )
        ->where('status', 'Settled')
        ->count();

        /*
        |--------------------------------------------------------------------------
        | RECENT VIOLATIONS
        |--------------------------------------------------------------------------
        */

        $recentViolations = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ])
        ->orderBy('violation_date', 'desc')
        ->orderBy('violation_time', 'desc')
        ->take(10)
        ->get();

        return view('bplo.dashboard', compact(
            'totalViolations',
            'pendingViolations',
            'reviewedViolations',
            'recentViolations'
        ));
    }
}