<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Total violations
        $totalViolations = Violation::count();

        // Today's violations
        $todayViolations = Violation::whereDate(
            'violation_date',
            Carbon::today()
        )->count();

        // This month's violations
        $monthlyViolations = Violation::whereMonth(
            'violation_date',
            Carbon::now()->month
        )
        ->whereYear(
            'violation_date',
            Carbon::now()->year
        )
        ->count();


        // Most common violation
        $mostCommonViolation = Violation::with('violationType')
    ->selectRaw('violation_type_id, COUNT(*) as total')
    ->whereNotNull('violation_type_id')
    ->groupBy('violation_type_id')
    ->orderByDesc('total')
    ->first();


        return view('admin.reports.index', compact(
            'totalViolations',
            'todayViolations',
            'monthlyViolations',
            'mostCommonViolation'
        ));
    }
}