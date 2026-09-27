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
            'violationTypes',
            'violationOtherTypes',
            'user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Apply Date Filters
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

        /*
        |--------------------------------------------------------------------------
        | Violation Type Filter
        |--------------------------------------------------------------------------
        |
        | Official violation:
        | - Primary violation_type_id
        | - Additional official violation
        |
        | Other:
        | - Primary custom Other violation
        | - Additional custom Other violation
        |
        */

        if ($request->filled('violation_type')) {

            $violationTypeFilter = $request->violation_type;

            $query->where(function ($q) use ($violationTypeFilter) {

                /*
                |--------------------------------------------------------------------------
                | Other
                |--------------------------------------------------------------------------
                */

                if ($violationTypeFilter === 'other') {

                    $q->where(function ($otherQuery) {

                        $otherQuery
                            ->whereNull('violation_type_id')
                            ->whereNotNull('other_violation')
                            ->where('other_violation', '!=', '');

                    })->orWhereHas('violationOtherTypes');

                }

                /*
                |--------------------------------------------------------------------------
                | Official Violation
                |--------------------------------------------------------------------------
                */

                else {

                    $q->where(
                        'violation_type_id',
                        $violationTypeFilter
                    )

                    ->orWhereHas('violationTypes', function ($typeQuery) use ($violationTypeFilter) {

                        $typeQuery->where(
                            'violation_types.id',
                            $violationTypeFilter
                        );

                    });
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Officer Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('officer')) {

            $query->where(
                'user_id',
                $request->officer
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Location Filter
        |--------------------------------------------------------------------------
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

        /*
        |--------------------------------------------------------------------------
        | Get Violations For Violation-Type Analytics
        |--------------------------------------------------------------------------
        |
        | One violation record may contain:
        |
        | 1. Primary official violation
        | 2. Primary custom Other violation
        | 3. Additional official violations
        | 4. Additional custom Other violations
        |
        */

        $analyticsViolations = (clone $query)
            ->with([
                'violationType',
                'violationTypes',
                'violationOtherTypes'
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Helper: Get All Violation Names
        |--------------------------------------------------------------------------
        */

        $getViolationNames = function ($violation) {

            $names = [];

            /*
            |--------------------------------------------------------------------------
            | Primary Official Violation
            |--------------------------------------------------------------------------
            */

            if ($violation->violationType) {

                $name = trim(
                    (string) ($violation->violationType->name ?? '')
                );

                if ($name !== '') {
                    $names[] = $name;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Primary Custom Other Violation
            |--------------------------------------------------------------------------
            */

            if (!empty($violation->other_violation)) {

                $name = trim(
                    (string) $violation->other_violation
                );

                if ($name !== '') {
                    $names[] = $name;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Additional Official Violations
            |--------------------------------------------------------------------------
            */

            if ($violation->violationTypes) {

                foreach ($violation->violationTypes as $type) {

                    $name = trim(
                        (string) ($type->name ?? '')
                    );

                    if ($name !== '') {
                        $names[] = $name;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Additional Custom Other Violations
            |--------------------------------------------------------------------------
            */

            if ($violation->violationOtherTypes) {

                foreach ($violation->violationOtherTypes as $otherType) {

                    $name = trim(
                        (string) ($otherType->name ?? '')
                    );

                    if ($name !== '') {
                        $names[] = $name;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Remove Duplicate Names
            |--------------------------------------------------------------------------
            */

            $uniqueNames = [];

            foreach ($names as $name) {

                $normalized = mb_strtolower(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim($name)
                    )
                );

                if (!isset($uniqueNames[$normalized])) {

                    $uniqueNames[$normalized] = $name;
                }
            }

            return array_values($uniqueNames);
        };

        /*
        |--------------------------------------------------------------------------
        | Count Violation Types
        |--------------------------------------------------------------------------
        */

        $violationTypeCounts = [];

        foreach ($analyticsViolations as $violation) {

            $names = $getViolationNames($violation);

            foreach ($names as $name) {

                $normalized = mb_strtolower(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim($name)
                    )
                );

                if (!isset($violationTypeCounts[$normalized])) {

                    $violationTypeCounts[$normalized] = [
                        'name' => $name,
                        'total' => 0,
                    ];
                }

                $violationTypeCounts[$normalized]['total']++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Convert Violation Type Counts To Collection
        |--------------------------------------------------------------------------
        |
        | The existing Reports Blade expects:
        |
        | $item->violationType->name
        |
        | Therefore, create a compatible object structure.
        |
        */

        $violationTypes = collect($violationTypeCounts)
            ->sortByDesc('total')
            ->map(function ($item) {

                $result = new \stdClass();

                $result->violationType = new \stdClass();

                $result->violationType->name =
                    $item['name'];

                $result->total =
                    $item['total'];

                return $result;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Most Common Violation
        |--------------------------------------------------------------------------
        */

        $mostCommonViolation = $violationTypes->first();

        /*
        |--------------------------------------------------------------------------
        | Monthly Violation Trend
        |--------------------------------------------------------------------------
        */

        $monthlyTrend = (clone $query)
            ->select(
                DB::raw('MONTH(violation_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('violation_date')
            ->groupBy('month')
            ->orderBy('month')
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
        | Filter Dropdown - Official Violation Types
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

                $q->where(
                    'name',
                    'POSO Enforcer'
                );

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
        | Return Reports View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.index',
            compact(
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
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export Filtered Reports To CSV
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
            'violationTypes',
            'violationOtherTypes',
            'user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Apply Date Filters
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

        /*
        |--------------------------------------------------------------------------
        | Apply Violation Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('violation_type')) {

            $violationTypeFilter =
                $request->violation_type;

            $query->where(function ($q) use ($violationTypeFilter) {

                /*
                |--------------------------------------------------------------------------
                | Other
                |--------------------------------------------------------------------------
                */

                if ($violationTypeFilter === 'other') {

                    $q->where(function ($otherQuery) {

                        $otherQuery
                            ->whereNull('violation_type_id')
                            ->whereNotNull('other_violation')
                            ->where('other_violation', '!=', '');

                    })->orWhereHas('violationOtherTypes');

                }

                /*
                |--------------------------------------------------------------------------
                | Official Violation
                |--------------------------------------------------------------------------
                */

                else {

                    $q->where(
                        'violation_type_id',
                        $violationTypeFilter
                    )

                    ->orWhereHas('violationTypes', function ($typeQuery) use ($violationTypeFilter) {

                        $typeQuery->where(
                            'violation_types.id',
                            $violationTypeFilter
                        );

                    });
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Officer Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('officer')) {

            $query->where(
                'user_id',
                $request->officer
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Location Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('location')) {

            $query->where(
                'location',
                $request->location
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Filtered Violations
        |--------------------------------------------------------------------------
        */

        $violations = $query
            ->orderByDesc('violation_date')
            ->orderByDesc('violation_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Create Excel-Compatible CSV
        |--------------------------------------------------------------------------
        */

        $filename =
            'traffic-enforcenet-report-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        $headers = [

            'Content-Type' =>
                'text/csv; charset=UTF-8',

            'Content-Disposition' =>
                'attachment; filename="' .
                $filename .
                '"',

            'Pragma' =>
                'no-cache',

            'Cache-Control' =>
                'must-revalidate, post-check=0, pre-check=0',

            'Expires' =>
                '0',
        ];

        /*
        |--------------------------------------------------------------------------
        | CSV Columns
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Stream CSV
        |--------------------------------------------------------------------------
        */

        return response()->stream(
            function () use ($violations, $columns) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                |--------------------------------------------------------------------------
                | UTF-8 BOM
                |--------------------------------------------------------------------------
                */

                fprintf(
                    $handle,
                    chr(0xEF) .
                    chr(0xBB) .
                    chr(0xBF)
                );

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */

                fputcsv(
                    $handle,
                    $columns
                );

                /*
                |--------------------------------------------------------------------------
                | CSV Rows
                |--------------------------------------------------------------------------
                */

                foreach ($violations as $violation) {

                    /*
                    |--------------------------------------------------------------------------
                    | Driver Name
                    |--------------------------------------------------------------------------
                    */

                    $driverName = '';

                    if ($violation->driver) {

                        $driverName = trim(

                            ($violation->driver->first_name ?? '') .
                            ' ' .
                            ($violation->driver->middle_name ?? '') .
                            ' ' .
                            ($violation->driver->last_name ?? '')

                        );

                        if ($driverName === '') {

                            $driverName =
                                $violation->driver->name ?? '';
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | License Code
                    |--------------------------------------------------------------------------
                    */

                    $licenseCode = '';

                    if ($violation->driver) {

                        $licenseCode =
                            $violation->driver->license_code ??
                            $violation->driver->license_number ??
                            '';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Plate Number
                    |--------------------------------------------------------------------------
                    */

                    $plateNumber = '';

                    if ($violation->vehicle) {

                        $plateNumber =
                            $violation->vehicle->plate_number ??
                            '';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Vehicle Type
                    |--------------------------------------------------------------------------
                    */

                    $vehicleType = '';

                    if ($violation->vehicle) {

                        $vehicleType =
                            $violation->vehicle->vehicle_type ??
                            '';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Violation Type
                    |--------------------------------------------------------------------------
                    |
                    | Include:
                    |
                    | - Primary official
                    | - Primary Other
                    | - Additional official
                    | - Additional Other
                    |
                    */

                    $violationNames = [];

                    /*
                    |--------------------------------------------------------------------------
                    | Primary Official Violation
                    |--------------------------------------------------------------------------
                    */

                    if ($violation->violationType) {

                        $name = trim(
                            (string) (
                                $violation
                                    ->violationType
                                    ->name ?? ''
                            )
                        );

                        if ($name !== '') {

                            $violationNames[] =
                                $name;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Primary Custom Other
                    |--------------------------------------------------------------------------
                    */

                    if (!empty(
                        $violation->other_violation
                    )) {

                        $name = trim(
                            (string)
                            $violation->other_violation
                        );

                        if ($name !== '') {

                            $violationNames[] =
                                $name;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Additional Official Violations
                    |--------------------------------------------------------------------------
                    */

                    if ($violation->violationTypes) {

                        foreach (
                            $violation->violationTypes
                            as $type
                        ) {

                            $name = trim(
                                (string) (
                                    $type->name ?? ''
                                )
                            );

                            if ($name !== '') {

                                $violationNames[] =
                                    $name;
                            }
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Additional Custom Other Violations
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $violation->violationOtherTypes
                    ) {

                        foreach (
                            $violation->violationOtherTypes
                            as $otherType
                        ) {

                            $name = trim(
                                (string) (
                                    $otherType->name ?? ''
                                )
                            );

                            if ($name !== '') {

                                $violationNames[] =
                                    $name;
                            }
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Remove Duplicate Violation Names
                    |--------------------------------------------------------------------------
                    */

                    $uniqueViolationNames = [];

                    foreach (
                        $violationNames
                        as $name
                    ) {

                        $normalized = mb_strtolower(
                            preg_replace(
                                '/\s+/',
                                ' ',
                                trim($name)
                            )
                        );

                        if (
                            !isset(
                                $uniqueViolationNames[
                                    $normalized
                                ]
                            )
                        ) {

                            $uniqueViolationNames[
                                $normalized
                            ] = $name;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Final Violation Type Text
                    |--------------------------------------------------------------------------
                    */

                    $violationType = implode(
                        ', ',
                        array_values(
                            $uniqueViolationNames
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Recorded By
                    |--------------------------------------------------------------------------
                    */

                    $recordedBy = '';

                    if ($violation->user) {

                        $recordedBy =
                            $violation->user->name ??
                            '';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Write CSV Row
                    |--------------------------------------------------------------------------
                    */

                    fputcsv(
                        $handle,
                        [

                            $violation->ticket_number ??
                                '',

                            $driverName,

                            $licenseCode,

                            $plateNumber,

                            $vehicleType,

                            $violationType,

                            $recordedBy,

                            $violation->violation_date ??
                                '',

                            $violation->violation_time ??
                                '',

                            $violation->location ??
                                '',

                            $violation->latitude ??
                                '',

                            $violation->longitude ??
                                '',

                            $violation->remarks ??
                                '',

                            $violation->status ??
                                '',
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Close CSV Stream
                |--------------------------------------------------------------------------
                */

                fclose($handle);
            },
            200,
            $headers
        );
    }
}