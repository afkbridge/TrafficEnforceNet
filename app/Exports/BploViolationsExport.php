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
            ->map(function ($violation) {

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
                | LAST NAME,FIRST NAME M
                |
                | Example:
                | MENDOZA,REGINA B
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

                $middleInitial = '';

                if (!empty($middleName)) {
                    $middleInitial = mb_substr(
                        $middleName,
                        0,
                        1
                    );
                }

                $violatorName = '';

                if (!empty($surname)) {
                    $violatorName .= $surname . ',';
                }

                if (!empty($firstName)) {
                    $violatorName .= $firstName;
                }

                if (!empty($middleInitial)) {
                    $violatorName .= ' ' . $middleInitial;
                }

                if (empty(trim($violatorName, " ,"))) {
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
                |
                | IMPORTANT:
                | Every value is converted to UPPERCASE.
                |
                */
                return [

                    // 1. DATE OF APPREHENSION
                    strtoupper(
                        $violation->violation_date ?? 'N/A'
                    ),

                    // 2. TICKET NUMBER
                    strtoupper(
                        $violation->ticket_number ?? 'N/A'
                    ),

                    // 3. VEHICLE TYPE
                    strtoupper(
                        $violation->vehicle->vehicle_type ?? 'N/A'
                    ),

                    // 4. PLATE NUMBER
                    strtoupper(
                        $violation->vehicle->plate_number ?? 'N/A'
                    ),

                    // 5. PLACE OF APPREHENSION
                    strtoupper(
                        $violation->location ?? 'N/A'
                    ),

                    // 6. VIOLATION
                    strtoupper(
                        $violationNames
                    ),

                    // 7. NAME
                    strtoupper(
                        $violatorName
                    ),

                    // 8. ADDRESS
                    strtoupper(
                        $violation->driver->address ?? 'N/A'
                    ),

                    // 9. LICENSE NUMBER
                    strtoupper(
                        $violation->driver->license_number ?? 'N/A'
                    ),

                    // 10. APPREHENDING OFFICER
                    strtoupper(
                        $apprehendingOfficer
                    ),

                    // 11. REMARKS
                    strtoupper(
                        $violation->remarks ?? 'N/A'
                    ),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date of Apprehension',
            'TCT #',
            'Vehicle Type',
            'Plate #/ Body #',
            'Place of Apprehension',
            'Violation',
            'NAME',
            'Address',
            'LICENSE N0',
            'Apprehending Officer',
            'Remarks',
        ];
    }
}