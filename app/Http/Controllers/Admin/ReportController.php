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
    /*
    |--------------------------------------------------------------------------
    | Reports Dashboard
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Export Filtered Reports to Excel
    |--------------------------------------------------------------------------
    */

    public function exportExcel(Request $request)
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
        | Apply The Same Filters Used By The Reports Page
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

        if ($request->filled('location')) {
            $query->where(
                'location',
                $request->location
            );
        }

        $violations = $query
            ->orderByDesc('violation_date')
            ->orderByDesc('violation_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Create Excel-Compatible CSV
        |--------------------------------------------------------------------------
        |
        | CSV opens directly in Microsoft Excel.
        | This avoids requiring an additional Excel package.
        |
        */

        $filename = 'traffic-enforcenet-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'Ticket Number',
            'Driver Name',
            'License Code',
            'Plate Number',
            'Vehicle Type',
            'Violation Type',
            'Recorded By',
            'Violation Date',
            'Violation Time',
            'Location',
            'Latitude',
            'Longitude',
            'Remarks',
            'Status',
        ];

        return response()->stream(function () use ($violations, $columns) {

            $handle = fopen('php://output', 'w');

            /*
            |--------------------------------------------------------------------------
            | UTF-8 BOM
            |--------------------------------------------------------------------------
            |
            | Helps Microsoft Excel correctly display names and locations.
            |
            */

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, $columns);

            foreach ($violations as $violation) {

                $driverName = '';

                if ($violation->driver) {
                    $driverName = trim(
                        ($violation->driver->first_name ?? '') . ' ' .
                        ($violation->driver->middle_name ?? '') . ' ' .
                        ($violation->driver->last_name ?? '')
                    );

                    if ($driverName === '') {
                        $driverName = $violation->driver->name ?? '';
                    }
                }

                $licenseCode = '';

                if ($violation->driver) {
                    $licenseCode =
                        $violation->driver->license_code ??
                        $violation->driver->license_number ??
                        '';
                }

                $plateNumber = '';

                if ($violation->vehicle) {
                    $plateNumber =
                        $violation->vehicle->plate_number ??
                        '';
                }

                $vehicleType = '';

                if ($violation->vehicle) {
                    $vehicleType =
                        $violation->vehicle->vehicle_type ??
                        '';
                }

                $violationType = '';

                if ($violation->violationType) {
                    $violationType =
                        $violation->violationType->name ??
                        '';
                }

                $recordedBy = '';

                if ($violation->user) {
                    $recordedBy =
                        $violation->user->name ??
                        '';
                }

                fputcsv($handle, [
                    $violation->ticket_number ?? '',
                    $driverName,
                    $licenseCode,
                    $plateNumber,
                    $vehicleType,
                    $violationType,
                    $recordedBy,
                    $violation->violation_date ?? '',
                    $violation->violation_time ?? '',
                    $violation->location ?? '',
                    $violation->latitude ?? '',
                    $violation->longitude ?? '',
                    $violation->remarks ?? '',
                    $violation->status ?? '',
                ]);
            }

            fclose($handle);

        }, 200, $headers);
    }
}