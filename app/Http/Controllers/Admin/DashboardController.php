<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Violation;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalViolations = Violation::count();

        $todayTickets = Violation::whereDate(
            'violation_date',
            today()
        )->count();

        $pendingCases = Violation::where(
            'status',
            'Pending'
        )->count();

        $activeEnforcers = User::whereHas('role', function ($query) {
            $query->where('name', 'POSO Enforcer');
        })->count();


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH DATE RANGE
        |--------------------------------------------------------------------------
        |
        | This range will be used by the dashboard's operational analytics.
        |
        */

        $currentMonthStart = now()->copy()->startOfMonth();
        $currentMonthEnd = now()->copy()->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | THIS MONTH
        |--------------------------------------------------------------------------
        */

        $thisMonthViolations = Violation::whereBetween(
            'violation_date',
            [
                $currentMonthStart->toDateString(),
                $currentMonthEnd->toDateString(),
            ]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | LAST MONTH
        |--------------------------------------------------------------------------
        */

        $lastMonthDate = now()->copy()->subMonth();

        $lastMonthViolations = Violation::whereMonth(
            'violation_date',
            $lastMonthDate->month
        )
            ->whereYear(
                'violation_date',
                $lastMonthDate->year
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MONTHLY VIOLATION TREND
        |--------------------------------------------------------------------------
        |
        | This remains a YEARLY chart.
        |
        | It shows January to December for the current year.
        |
        */

        $monthlyViolations = [];

        for ($month = 1; $month <= 12; $month++) {

            $monthlyViolations[] = Violation::whereMonth(
                'violation_date',
                $month
            )
                ->whereYear(
                    'violation_date',
                    now()->year
                )
                ->count();
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD CURRENT-MONTH VIOLATION DATA FOR ANALYTICS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Only current-month violation records are loaded here.
        |
        | This includes:
        |
        | 1. Primary official violation
        | 2. Primary custom "Other" violation
        | 3. Additional official violations
        | 4. Additional custom "Other" violations
        |
        */

        $violationsForAnalytics = Violation::with([
            'violationType',
            'violationTypes',
            'violationOtherTypes',
        ])
            ->whereBetween(
                'violation_date',
                [
                    $currentMonthStart->toDateString(),
                    $currentMonthEnd->toDateString(),
                ]
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | HELPER: GET ALL VIOLATION NAMES FROM ONE RECORD
        |--------------------------------------------------------------------------
        */

        $getViolationNames = function ($violation) {

            $names = [];


            /*
            |--------------------------------------------------------------------------
            | PRIMARY OFFICIAL VIOLATION
            |--------------------------------------------------------------------------
            */

            if ($violation->violationType) {

                $name = trim(
                    (string) $violation->violationType->name
                );

                if ($name !== '') {
                    $names[] = $name;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | PRIMARY CUSTOM "OTHER" VIOLATION
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
            | ADDITIONAL OFFICIAL VIOLATIONS
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
            | ADDITIONAL CUSTOM "OTHER" VIOLATIONS
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
            | REMOVE DUPLICATES FROM SAME TICKET
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
        | CURRENT MONTH - TOP VIOLATION TYPES
        |--------------------------------------------------------------------------
        |
        | Counts only violations recorded during the current month.
        |
        | Custom "Other" violations are included.
        |
        */

        $violationTypeTotals = [];

        foreach ($violationsForAnalytics as $violation) {

            $violationNames = $getViolationNames($violation);

            foreach ($violationNames as $name) {

                $normalized = mb_strtolower(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim($name)
                    )
                );

                if (!isset($violationTypeTotals[$normalized])) {

                    $violationTypeTotals[$normalized] = [
                        'name' => trim($name),
                        'total' => 0,
                    ];
                }

                $violationTypeTotals[$normalized]['total']++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SORT VIOLATION TYPES
        |--------------------------------------------------------------------------
        */

        usort(
            $violationTypeTotals,
            function ($a, $b) {

                return $b['total'] <=> $a['total'];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PREPARE TOP VIOLATION CHART DATA
        |--------------------------------------------------------------------------
        |
        | All current-month violation types are included.
        |
        */

        $violationLabels = collect($violationTypeTotals)
            ->pluck('name')
            ->values();

        $violationCounts = collect($violationTypeTotals)
            ->pluck('total')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PEAK VIOLATION PERIODS
        |--------------------------------------------------------------------------
        |
        | Current month only.
        |
        */

        $peakPeriods = [
            '6 AM - 9 AM' => [6, 9],
            '9 AM - 12 PM' => [9, 12],
            '12 PM - 3 PM' => [12, 15],
            '3 PM - 6 PM' => [15, 18],
            '6 PM - 9 PM' => [18, 21],
            '9 PM - 12 AM' => [21, 24],
        ];

        $peakPeriodLabels = [];
        $peakPeriodCounts = [];

        foreach ($peakPeriods as $label => $hours) {

            $count = Violation::whereBetween(
                'violation_date',
                [
                    $currentMonthStart->toDateString(),
                    $currentMonthEnd->toDateString(),
                ]
            )
                ->whereRaw(
                    'HOUR(created_at) >= ?',
                    [$hours[0]]
                )
                ->whereRaw(
                    'HOUR(created_at) < ?',
                    [$hours[1]]
                )
                ->count();

            $peakPeriodLabels[] = $label;
            $peakPeriodCounts[] = $count;
        }


        /*
        |--------------------------------------------------------------------------
        | PEAK VIOLATION PERIOD INSIGHT
        |--------------------------------------------------------------------------
        */

        $peakPeriodIndex = !empty($peakPeriodCounts)
            ? array_keys(
                $peakPeriodCounts,
                max($peakPeriodCounts)
            )[0]
            : null;

        $peakPeriodName = $peakPeriodIndex !== null
            ? $peakPeriodLabels[$peakPeriodIndex]
            : 'No data';

        $peakPeriodCount = $peakPeriodIndex !== null
            ? $peakPeriodCounts[$peakPeriodIndex]
            : 0;


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH - ENFORCER ACTIVITY
        |--------------------------------------------------------------------------
        */

        $enforcerActivity = Violation::with('user')
            ->selectRaw('user_id, COUNT(*) as total')
            ->whereNotNull('user_id')
            ->whereBetween(
                'violation_date',
                [
                    $currentMonthStart->toDateString(),
                    $currentMonthEnd->toDateString(),
                ]
            )
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $enforcerLabels = $enforcerActivity->map(function ($item) {

            if ($item->user) {

                return trim(
                    ($item->user->first_name ?? '') .
                    ' ' .
                    ($item->user->last_name ?? '')
                ) ?: ($item->user->name ?? 'Unknown');
            }

            return 'Unknown';

        })->values();

        $enforcerCounts = $enforcerActivity
            ->pluck('total')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH - VIOLATION HOTSPOT DATA
        |--------------------------------------------------------------------------
        */

        $violationLocations = Violation::with([
            'violationType',
            'violationTypes',
            'violationOtherTypes',
        ])
            ->select(
                'id',
                'latitude',
                'longitude',
                'violation_type_id',
                'other_violation'
            )
            ->whereBetween(
                'violation_date',
                [
                    $currentMonthStart->toDateString(),
                    $currentMonthEnd->toDateString(),
                ]
            )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();


        $hotspotGroups = [];

        foreach ($violationLocations as $violation) {

            $latitude = (float) $violation->latitude;
            $longitude = (float) $violation->longitude;


            /*
            |--------------------------------------------------------------------------
            | GROUP NEARBY LOCATIONS
            |--------------------------------------------------------------------------
            */

            $gridLatitude = round($latitude, 3);
            $gridLongitude = round($longitude, 3);

            $groupKey = $gridLatitude . ',' . $gridLongitude;


            if (!isset($hotspotGroups[$groupKey])) {

                $hotspotGroups[$groupKey] = [
                    'latitude' => 0,
                    'longitude' => 0,
                    'violations' => 0,
                    'violation_types' => [],
                ];
            }


            $hotspotGroups[$groupKey]['latitude'] += $latitude;
            $hotspotGroups[$groupKey]['longitude'] += $longitude;
            $hotspotGroups[$groupKey]['violations']++;


            /*
            |--------------------------------------------------------------------------
            | COUNT VIOLATION TYPES IN HOTSPOT
            |--------------------------------------------------------------------------
            */

            $violationNames = $getViolationNames($violation);

            foreach ($violationNames as $violationTypeName) {

                $normalized = mb_strtolower(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim($violationTypeName)
                    )
                );

                if (
                    !isset(
                        $hotspotGroups[$groupKey]['violation_types'][$normalized]
                    )
                ) {

                    $hotspotGroups[$groupKey]['violation_types'][$normalized] = [
                        'name' => trim($violationTypeName),
                        'total' => 0,
                    ];
                }

                $hotspotGroups[$groupKey]['violation_types'][$normalized]['total']++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PREPARE HOTSPOT DATA FOR MAP
        |--------------------------------------------------------------------------
        */

        $hotspots = collect($hotspotGroups)
            ->map(function ($group) {

                $count = $group['violations'];


                /*
                |--------------------------------------------------------------------------
                | DETERMINE HOTSPOT LEVEL
                |--------------------------------------------------------------------------
                */

                if ($count >= 8) {

                    $level = 'high';

                } elseif ($count >= 4) {

                    $level = 'moderate';

                } else {

                    $level = 'low';
                }


                /*
                |--------------------------------------------------------------------------
                | FIND MOST COMMON VIOLATION
                |--------------------------------------------------------------------------
                */

                $mostCommonViolation = 'Unknown';

                if (!empty($group['violation_types'])) {

                    uasort(
                        $group['violation_types'],
                        function ($a, $b) {

                            return $b['total'] <=> $a['total'];
                        }
                    );

                    $firstViolation = reset(
                        $group['violation_types']
                    );

                    if ($firstViolation) {

                        $mostCommonViolation =
                            $firstViolation['name'];
                    }
                }


                return [
                    'latitude' =>
                        $group['latitude'] / $count,

                    'longitude' =>
                        $group['longitude'] / $count,

                    'violation_count' =>
                        $count,

                    'level' =>
                        $level,

                    'most_common_violation' =>
                        $mostCommonViolation,
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH - VIOLATIONS BY LOCATION
        |--------------------------------------------------------------------------
        */

        $locationViolations = Violation::selectRaw(
            "COALESCE(
                NULLIF(TRIM(location), ''),
                'Location Not Specified'
            ) as location_name,
            COUNT(*) as total"
        )
            ->whereBetween(
                'violation_date',
                [
                    $currentMonthStart->toDateString(),
                    $currentMonthEnd->toDateString(),
                ]
            )
            ->groupBy('location_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $locationLabels = $locationViolations
            ->pluck('location_name')
            ->values();

        $locationCounts = $locationViolations
            ->pluck('total')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RECENT VIOLATIONS
        |--------------------------------------------------------------------------
        |
        | Keeps the dashboard compact.
        |
        | Shows the latest 8 records regardless of month.
        |
        */

        $recentViolations = Violation::with([
            'violationType',
            'violationTypes',
            'violationOtherTypes',
            'driver',
        ])
            ->orderByDesc('violation_date')
            ->orderByDesc('violation_time')
            ->orderByDesc('id')
            ->limit(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MONTHLY COMPARISON
        |--------------------------------------------------------------------------
        */

        if ($lastMonthViolations > 0) {

            $monthlyChange = (
                ($thisMonthViolations - $lastMonthViolations)
                / $lastMonthViolations
            ) * 100;

        } else {

            $monthlyChange = $thisMonthViolations > 0
                ? 100
                : 0;
        }


        /*
        |--------------------------------------------------------------------------
        | MOST COMMON VIOLATION
        |--------------------------------------------------------------------------
        |
        | Current month only.
        |
        | Custom Other violations are included.
        |
        */

        $mostCommonViolation =
            $violationTypeTotals[0] ?? null;

        $mostCommonViolationName =
            $mostCommonViolation['name'] ?? 'No data';


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH LABEL
        |--------------------------------------------------------------------------
        |
        | This can be used later by the Blade view for chart titles.
        |
        */

        $currentMonthLabel = now()->format('F Y');


        /*
        |--------------------------------------------------------------------------
        | SEND DATA TO VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard.index',
            compact(
                'totalViolations',
                'todayTickets',
                'pendingCases',
                'activeEnforcers',
                'thisMonthViolations',
                'lastMonthViolations',
                'monthlyChange',
                'monthlyViolations',
                'violationLabels',
                'violationCounts',
                'peakPeriodLabels',
                'peakPeriodCounts',
                'peakPeriodName',
                'peakPeriodCount',
                'enforcerLabels',
                'enforcerCounts',
                'hotspots',
                'locationLabels',
                'locationCounts',
                'recentViolations',
                'mostCommonViolationName',
                'currentMonthLabel'
            )
        );
    }
}
