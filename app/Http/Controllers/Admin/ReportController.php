<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\ViolationType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Apply Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {
            $query->whereDate('violation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('violation_date', '<=', $request->date_to);
        }

        if ($request->filled('violation_type')) {
            $query->where('violation_type_id', $request->violation_type);
        }

        if ($request->filled('officer')) {
            $query->where('user_id', $request->officer);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        /*
        |--------------------------------------------------------------------------
        | Summary Cards (Filtered)
        |--------------------------------------------------------------------------
        */

        $totalViolations = (clone $query)->count();

        $todayViolations = (clone $query)
            ->whereDate('violation_date', Carbon::today())
            ->count();

        $monthlyViolations = (clone $query)
            ->whereMonth('violation_date', Carbon::now()->month)
            ->whereYear('violation_date', Carbon::now()->year)
            ->count();

        $mostCommonViolation = (clone $query)
            ->selectRaw('violation_type_id, COUNT(*) as total')
            ->whereNotNull('violation_type_id')
            ->groupBy('violation_type_id')
            ->orderByDesc('total')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Monthly Trend (Temporary)
        |--------------------------------------------------------------------------
        */

        $monthlyTrend = Violation::select(
                DB::raw('MONTH(violation_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('violation_date', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Violation Type Distribution (Temporary)
        |--------------------------------------------------------------------------
        */

        $violationTypes = Violation::with('violationType')
            ->select(
                'violation_type_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('violation_type_id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Status Summary (Temporary)
        |--------------------------------------------------------------------------
        */

        $statusSummary = Violation::select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown Data
        |--------------------------------------------------------------------------
        */

        $filterViolationTypes = ViolationType::orderBy('name')->get();

        $filterOfficers = User::whereHas('role', function ($q) {
                $q->where('name', 'POSO Enforcer');
            })
            ->orderBy('name')
            ->get();

        $filterLocations = Violation::select('location')
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('admin.reports.index', compact(
            'totalViolations',
            'todayViolations',
            'monthlyViolations',
            'mostCommonViolation',
            'monthlyTrend',
            'violationTypes',
            'statusSummary',
            'filterViolationTypes',
            'filterOfficers',
            'filterLocations'
        ));
    }
}