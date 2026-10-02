<?php

namespace App\Exports;

use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BploViolationsExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $status;
    protected $dateFrom;
    protected $dateTo;

    public function __construct(
        $search = '',
        $status = 'all',
        $dateFrom = null,
        $dateTo = null
    ) {
        $this->search = trim($search);
        $this->status = $status;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'user',
        ]);

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */
        if ($this->status === 'Pending' || $this->status === 'Settled') {
            $query->where('status', $this->status);
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH FILTER
        |--------------------------------------------------------------------------
        */
        if (!empty($this->search)) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'ticket_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('driver', function ($driver) use ($search) {

                    $driver->where(function ($nameQuery) use ($search) {

                        $nameQuery
                            ->whereRaw(
                                "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                                ["%{$search}%"]
                            )

                            ->orWhereRaw(
                                "CONCAT(first_name, ' ', last_name) LIKE ?",
                                ["%{$search}%"]
                            )

                            ->orWhere(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'middle_name',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'license_number',
                                'like',
                                "%{$search}%"
                            );
                    });
                })

                ->orWhereHas('vehicle', function ($vehicle) use ($search) {

                    $vehicle->where(
                        'plate_number',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FROM FILTER
        |--------------------------------------------------------------------------
        */
        if (!empty($this->dateFrom)) {
            $query->whereDate(
                'violation_date',
                '>=',
                $this->dateFrom
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE TO FILTER
        |--------------------------------------------------------------------------
        */
        if (!empty($this->dateTo)) {
            $query->whereDate(
                'violation_date',
                '<=',
                $this->dateTo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GET AND FORMAT DATA
        |--------------------------------------------------------------------------
        */
        return $query
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->get()
            ->map(function ($violation, $index) {

                /*
                |--------------------------------------------------------------------------
                | VIOLATION NAMES
                |--------------------------------------------------------------------------
                */
                $violationNames = $violation->violationTypes
                    ->pluck('name')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                /*
                |--------------------------------------------------------------------------
                | FALLBACK TO SINGLE VIOLATION TYPE
                |--------------------------------------------------------------------------
                */
                if (empty($violationNames)) {
                    $violationNames =
                        $violation->violationType->name ?? 'N/A';
                }

                /*
                |--------------------------------------------------------------------------
                | VIOLATOR NAME
                |--------------------------------------------------------------------------
                |
                | Format:
                | LAST NAME, FIRST NAME MIDDLE NAME
                |
                | Example:
                | REYES, PEDRO SANTOS
                |
                */
                $surname = trim(
                    $violation->driver->last_name ?? ''
                );

                $firstName = trim(
                    $violation->driver->first_name ?? ''
                );

                $middleName = trim(
                    $violation->driver->middle_name ?? ''
                );

                $givenNames = trim(
                    implode(' ', array_filter([
                        $firstName,
                        $middleName,
                    ]))
                );

                if (!empty($surname) && !empty($givenNames)) {
                    $violatorName = $surname . ', ' . $givenNames;
                } elseif (!empty($surname)) {
                    $violatorName = $surname;
                } elseif (!empty($givenNames)) {
                    $violatorName = $givenNames;
                } else {
                    $violatorName = 'N/A';
                }

                /*
                |--------------------------------------------------------------------------
                | APPREHENDING OFFICER
                |--------------------------------------------------------------------------
                |
                | Violation.user_id -> User relationship
                |
                */
                $apprehendingOfficer = 'N/A';

                if ($violation->user) {

                    /*
                    | Use the user's name if available.
                    */
                    if (!empty($violation->user->name)) {

                        $apprehendingOfficer =
                            $violation->user->name;

                    } else {

                        /*
                        | Fallback if the User model does not
                        | have a "name" value.
                        */
                        $apprehendingOfficer = trim(
                            implode(' ', array_filter([
                                $violation->user->first_name ?? null,
                                $violation->user->middle_name ?? null,
                                $violation->user->last_name ?? null,
                            ]))
                        );

                        if (empty($apprehendingOfficer)) {
                            $apprehendingOfficer = 'N/A';
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | RETURN EXCEL ROW
                |--------------------------------------------------------------------------
                */
                return [

                    // 1. NO
                    $index + 1,

                    // 2. DATE OF APPREHENSION
                    strtoupper(
                        $violation->violation_date ?? 'N/A'
                    ),

                    // 3. TCT #
                    strtoupper(
                        $violation->ticket_number ?? 'N/A'
                    ),

                    // 4. VEHICLE TYPE
                    strtoupper(
                        $violation->vehicle->vehicle_type ?? 'N/A'
                    ),

                    // 5. PLATE # / BODY #
                    strtoupper(
                        $violation->vehicle->plate_number ?? 'N/A'
                    ),

                    // 6. PLACE OF APPREHENSION
                    strtoupper(
                        $violation->location ?? 'N/A'
                    ),

                    // 7. VIOLATION
                    strtoupper(
                        $violationNames
                    ),

                    // 8. NAME
                    strtoupper(
                        $violatorName
                    ),

                    // 9. ADDRESS
                    strtoupper(
                        $violation->driver->address ?? 'N/A'
                    ),

                    // 10. LICENSE NO.
                    strtoupper(
                        $violation->driver->license_number ?? 'N/A'
                    ),

                    // 11. APPREHENDING OFFICER
                    strtoupper(
                        $apprehendingOfficer
                    ),

                    // 12. REMARKS
                    strtoupper(
                        $violation->remarks ?? 'N/A'
                    ),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No',
            'Date of Apprehension',
            'TCT #',
            'Vehicle Type',
            'Plate # / Body #',
            'Place of Apprehension',
            'Violation',
            'Name',
            'Address',
            'LICENSE NO.',
            'Apprehending Officer',
            'Remarks',
        ];
    }
}