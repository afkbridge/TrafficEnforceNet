<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Support\Facades\DB;
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



        // Monthly violation trend
        $monthlyTrend = Violation::select(
                DB::raw('MONTH(violation_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear(
                'violation_date',
                Carbon::now()->year
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();



        // Violation type distribution
        $violationTypes = Violation::with('violationType')
            ->select(
                'violation_type_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('violation_type_id')
            ->get();



        // Status summary
        $statusSummary = Violation::select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->get();



        return view('admin.reports.index', compact(
            'totalViolations',
            'todayViolations',
            'monthlyViolations',
            'mostCommonViolation',
            'monthlyTrend',
            'violationTypes',
            'statusSummary'
        ));
    }
}