<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
$dateFilter = $request->get('date_filter', 'all');
$specificDate = $request->get('specific_date');
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
        ->where('status', 'Completed')
        ->count();


        /*
        |--------------------------------------------------------------------------
        | RECENT VIOLATIONS
        |--------------------------------------------------------------------------
        */

        $recentViolationsQuery = Violation::with([
    'driver',
    'vehicle',
    'violationType',
    'user'
]);

switch ($dateFilter) {

    case 'today':
        $recentViolationsQuery->whereDate(
            'violation_date',
            $now->toDateString()
        );
        break;

    case 'yesterday':
        $recentViolationsQuery->whereDate(
            'violation_date',
            $now->copy()->subDay()->toDateString()
        );
        break;

    case 'this_week':
        $recentViolationsQuery->whereBetween(
            'violation_date',
            [
                $now->copy()->startOfWeek()->toDateString(),
                $now->copy()->endOfWeek()->toDateString()
            ]
        );
        break;

    case 'this_month':
        $recentViolationsQuery
            ->whereYear('violation_date', $now->year)
            ->whereMonth('violation_date', $now->month);
        break;

    case 'specific_date':
        if (!empty($specificDate)) {
            $recentViolationsQuery->whereDate(
                'violation_date',
                $specificDate
            );
        }
        break;
}

$recentViolations = $recentViolationsQuery
    ->orderBy('violation_date', 'desc')
    ->orderBy('violation_time', 'desc')
    ->take(10)
    ->get();


        return view('bplo.dashboard', compact(
    'totalViolations',
    'pendingViolations',
    'reviewedViolations',
    'recentViolations',
    'dateFilter',
    'specificDate'
));
    }
}