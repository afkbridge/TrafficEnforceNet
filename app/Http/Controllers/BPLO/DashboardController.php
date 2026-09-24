<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalViolations = Violation::count();

        $pendingViolations = Violation::where('status', 'Pending')
            ->count();

        $settledViolations = Violation::where('status', 'Settled')
            ->count();

        $thisMonthViolations = Violation::whereYear('violation_date', $now->year)
            ->whereMonth('violation_date', $now->month)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH TRANSACTION STATISTICS
        |--------------------------------------------------------------------------
        */

        $thisMonthPending = Violation::whereYear('violation_date', $now->year)
            ->whereMonth('violation_date', $now->month)
            ->where('status', 'Pending')
            ->count();

        $thisMonthSettled = Violation::whereYear('violation_date', $now->year)
            ->whereMonth('violation_date', $now->month)
            ->where('status', 'Settled')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PREVIOUS MONTH COMPARISON
        |--------------------------------------------------------------------------
        */

        $previousMonth = $now->copy()->subMonth();

        $previousMonthViolations = Violation::whereYear(
                'violation_date',
                $previousMonth->year
            )
            ->whereMonth(
                'violation_date',
                $previousMonth->month
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | MONTHLY VIOLATION RECORDS
        |--------------------------------------------------------------------------
        */

        $monthlyViolations = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyViolations[] = Violation::whereYear(
                    'violation_date',
                    $now->year
                )
                ->whereMonth('violation_date', $month)
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | VIOLATION TYPE STATISTICS
        |--------------------------------------------------------------------------
        | Uses the violation_violation_type pivot so multiple violations
        | attached to one ticket are included.
        |--------------------------------------------------------------------------
        */

        $violationTypeStatistics = DB::table('violation_violation_type')
            ->join(
                'violation_types',
                'violation_violation_type.violation_type_id',
                '=',
                'violation_types.id'
            )
            ->select(
                'violation_types.name',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('violation_types.id', 'violation_types.name')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PENDING VS SETTLED
        |--------------------------------------------------------------------------
        */

        $statusStatistics = [
            'Pending' => $pendingViolations,
            'Settled' => $settledViolations,
        ];

        /*
        |--------------------------------------------------------------------------
        | RECENT VIOLATION RECORDS
        |--------------------------------------------------------------------------
        */

        $recentViolations = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'user'
        ])
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->take(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PENDING TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        $pendingRecords = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'user'
        ])
            ->where('status', 'Pending')
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TODAY'S ACTIVITY
        |--------------------------------------------------------------------------
        */

        $todayViolations = Violation::whereDate(
            'violation_date',
            today()
        )->count();

        $todayPending = Violation::whereDate(
                'violation_date',
                today()
            )
            ->where('status', 'Pending')
            ->count();

        $todaySettled = Violation::whereDate(
                'violation_date',
                today()
            )
            ->where('status', 'Settled')
            ->count();

        return view('bplo.dashboard', compact(
            'totalViolations',
            'pendingViolations',
            'settledViolations',
            'thisMonthViolations',
            'thisMonthPending',
            'thisMonthSettled',
            'previousMonthViolations',
            'monthlyViolations',
            'violationTypeStatistics',
            'statusStatistics',
            'recentViolations',
            'pendingRecords',
            'todayViolations',
            'todayPending',
            'todaySettled'
        ));
    }
}
