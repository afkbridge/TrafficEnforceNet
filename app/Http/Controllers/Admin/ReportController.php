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
            $query->whereDate(
                'violation_date',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'violation_date',
                '<=',
                $request->date_to
            );
        }

        if ($request->filled('violation_type')) {
            $query->where(
                'violation_type_id',
                $request->violation_type
            );
        }

        if ($request->filled('officer')) {
            $query->where(
                'user_id',
                $request->officer
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NOTE ABOUT STATUS
        |--------------------------------------------------------------------------
        |
        | Status is handled by BPLO, so it is intentionally NOT included
        | in the Admin/POSO Reports analytics or filters.
        |
        */

        if ($request->filled('location')) {
            $query->where(
                'location',
                $request->location
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Summary Cards
        |--------------------------------------------------------------------------
        */

        $totalViolations = (clone $query)->count();

        $todayViolations = (clone $query)
            ->whereDate(
                'violation_date',
                Carbon::today()
            )
            ->count();

        $monthlyViolations = (clone $query)
            ->whereMonth(
                'violation_date',
                Carbon::now()->month
            )
            ->whereYear(
                'violation_date',
                Carbon::now()->year
            )
            ->count();

        $mostCommonViolation = (clone $query)
            ->selectRaw(
                'violation_type_id, COUNT(*) as total'
            )
            ->whereNotNull('violation_type_id')
            ->groupBy('violation_type_id')
            ->orderByDesc('total')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Monthly Violation Trend
        |--------------------------------------------------------------------------
        */

        $monthlyTrend = (clone $query)
            ->select(
                DB::raw(
                    'MONTH(violation_date) as month'
                ),
                DB::raw(
                    'COUNT(*) as total'
                )
            )
            ->whereNotNull('violation_date')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Violation Type Distribution
        |--------------------------------------------------------------------------
        */

        $violationTypes = (clone $query)
            ->with('violationType')
            ->select(
                'violation_type_id',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('violation_type_id')
            ->groupBy('violation_type_id')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Violations By Location
        |--------------------------------------------------------------------------
        |
        | Used by the "Violations by Location" chart.
        |
        */

        $locationData = (clone $query)
            ->select(
                'location',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->groupBy('location')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Violations By Officer
        |--------------------------------------------------------------------------
        |
        | Uses the user who encoded/recorded the violation.
        |
        */

        $officerData = (clone $query)
            ->join(
                'users',
                'violations.user_id',
                '=',
                'users.id'
            )
            ->select(
                'violations.user_id',
                'users.name',
                DB::raw('COUNT(violations.id) as total')
            )
            ->whereNotNull('violations.user_id')
            ->groupBy(
                'violations.user_id',
                'users.name'
            )
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown - Violation Types
        |--------------------------------------------------------------------------
        */

        $filterViolationTypes = ViolationType::orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown - POSO Officers
        |--------------------------------------------------------------------------
        */

        $filterOfficers = User::whereHas('role', function ($q) {
                $q->where('name', 'POSO Enforcer');
            })
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown - Locations
        |--------------------------------------------------------------------------
        */

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

            'locationData',
            'officerData',

            'filterViolationTypes',
            'filterOfficers',
            'filterLocations'
        ));
    }
}