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
        | THIS MONTH
        |--------------------------------------------------------------------------
        */

        $thisMonthViolations = Violation::whereMonth(
            'violation_date',
            now()->month
        )
            ->whereYear(
                'violation_date',
                now()->year
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | LAST MONTH
        |--------------------------------------------------------------------------
        */

        $lastMonthViolations = Violation::whereMonth(
            'violation_date',
            now()->subMonth()->month
        )
            ->whereYear(
                'violation_date',
                now()->subMonth()->year
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MONTHLY VIOLATION TREND
        |--------------------------------------------------------------------------
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
        | TOP VIOLATION TYPES
        |--------------------------------------------------------------------------
        */

        $topViolations = Violation::with('violationType')
            ->selectRaw('violation_type_id, COUNT(*) as total')
            ->whereNotNull('violation_type_id')
            ->groupBy('violation_type_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();


        $violationLabels = $topViolations->map(function ($item) {

            return $item->violationType->name
                ?? 'Unknown';
        })->values();


        $violationCounts = $topViolations
            ->pluck('total')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PEAK VIOLATION HOURS
        |--------------------------------------------------------------------------
        |
        | Uses created_at because this contains the time the record
        | was actually entered into the system.
        |
        */

        /*
|--------------------------------------------------------------------------
| PEAK VIOLATION PERIODS
|--------------------------------------------------------------------------
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

            $count = Violation::whereMonth(
                'created_at',
                now()->month
            )
                ->whereYear(
                    'created_at',
                    now()->year
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
            ? array_keys($peakPeriodCounts, max($peakPeriodCounts))[0]
            : null;

        $peakPeriodName = $peakPeriodIndex !== null
            ? $peakPeriodLabels[$peakPeriodIndex]
            : 'No data';

        $peakPeriodCount = $peakPeriodIndex !== null
            ? $peakPeriodCounts[$peakPeriodIndex]
            : 0;

        /*
        |--------------------------------------------------------------------------
        | ENFORCER ACTIVITY
        |--------------------------------------------------------------------------
        */

        $enforcerActivity = Violation::with('user')
            ->selectRaw('user_id, COUNT(*) as total')
            ->whereNotNull('user_id')
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
                ) ?: $item->user->name ?? 'Unknown';
            }

            return 'Unknown';
        })->values();


        $enforcerCounts = $enforcerActivity
            ->pluck('total')
            ->values();


        /*
|--------------------------------------------------------------------------
| VIOLATION HOTSPOT DATA
|--------------------------------------------------------------------------
|
| Groups nearby violation records into hotspot areas.
|
*/

        $violationLocations = Violation::with('violationType')
            ->select(
                'id',
                'latitude',
                'longitude',
                'violation_type_id'
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
    |
    | Rounding to 3 decimal places groups locations roughly within
    | around 100 meters of each other.
    |
    */

            $gridLatitude = round($latitude, 3);
            $gridLongitude = round($longitude, 3);

            $groupKey = $gridLatitude . ',' . $gridLongitude;

            if (!isset($hotspotGroups[$groupKey])) {

                $hotspotGroups[$groupKey] = [

                    'latitude' => 0,

                    'longitude' => 0,

                    'violations' => 0,

                    'violation_types' => []

                ];
            }

            $hotspotGroups[$groupKey]['latitude'] += $latitude;

            $hotspotGroups[$groupKey]['longitude'] += $longitude;

            $hotspotGroups[$groupKey]['violations']++;


            /*
    |--------------------------------------------------------------------------
    | COUNT VIOLATION TYPES
    |--------------------------------------------------------------------------
    */

            $violationTypeName =
                $violation->violationType?->name
                ?? 'Unknown';

            if (
                !isset(
                    $hotspotGroups[$groupKey]['violation_types'][$violationTypeName]
                )
            ) {

                $hotspotGroups[$groupKey]['violation_types'][$violationTypeName] = 0;
            }

            $hotspotGroups[$groupKey]['violation_types'][$violationTypeName]++;
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

                    arsort($group['violation_types']);

                    $mostCommonViolation =
                        array_key_first(
                            $group['violation_types']
                        );
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
                    $mostCommonViolation

                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | RECENT VIOLATIONS
        |--------------------------------------------------------------------------
        */

        $recentViolations = Violation::with([
            'violationType',
            'driver'
        ])
            ->latest('violation_date')
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
        */

        $mostCommonViolation = $topViolations->first();

        $mostCommonViolationName =
            $mostCommonViolation?->violationType?->name
            ?? 'No data';


        /*
        |--------------------------------------------------------------------------
        | SEND DATA TO VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard.index', compact(

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

            'recentViolations',

            'mostCommonViolationName'


        ));
    }
}
