<?php

namespace App\Exports;

use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ViolationsExport implements FromCollection, WithHeadings
{
    protected $filter;
    protected $date;

    public function __construct($filter = null, $date = null)
    {
        $this->filter = $filter;
        $this->date = $date;
    }

    public function collection()
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'user'
        ]);

        // ==========================================================
        // FILTER TODAY'S VIOLATIONS
        // ==========================================================

        if ($this->filter == 'today') {
            $query->whereDate(
                'violation_date',
                today()
            );
        }

        // ==========================================================
        // FILTER SPECIFIC DATE
        // ==========================================================

        if ($this->date) {
            $query->whereDate(
                'violation_date',
                $this->date
            );
        }

        return $query
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->get()
            ->map(function ($violation) {

                // ==================================================
                // NAME
                // FORMAT:
                // SURNAME, FIRST NAME MIDDLE NAME
                //
                // Example:
                // MENDOZA, REGINA BINUYA
                // ==================================================

                $surname = trim(
                    $violation->driver->last_name ?? ''
                );

                $firstName = trim(
                    $violation->driver->first_name ?? ''
                );

                $middleName = trim(
                    $violation->driver->middle_name ?? ''
                );

                $violatorName = '';

                if (!empty($surname)) {
                    $violatorName .= $surname;
                }

                if (!empty($firstName)) {

                    if (!empty($violatorName)) {
                        $violatorName .= ', ';
                    }

                    $violatorName .= $firstName;
                }

                if (!empty($middleName)) {

                    if (
                        !empty($firstName) ||
                        !empty($surname)
                    ) {
                        $violatorName .= ' ';
                    }

                    $violatorName .= $middleName;
                }

                if (empty(trim($violatorName))) {
                    $violatorName = 'N/A';
                }

                // ==================================================
                // VIOLATION
                // SUPPORTS MULTIPLE VIOLATIONS
                // ==================================================

                $violationNames = $violation->violationTypes
                    ->pluck('name')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                if (empty($violationNames)) {

                    $violationNames =
                        $violation->violationType->name ?? '';

                }

                if (empty(trim($violationNames))) {
                    $violationNames = 'N/A';
                }

                // ==================================================
                // APPREHENDING OFFICER
                // ==================================================

                $apprehendingOfficer = 'N/A';

                if ($violation->user) {

                    if (!empty($violation->user->name)) {

                        $apprehendingOfficer =
                            $violation->user->name;

                    } else {

                        $apprehendingOfficer = trim(
                            implode(
                                ' ',
                                array_filter([
                                    $violation->user->first_name ?? null,
                                    $violation->user->middle_name ?? null,
                                    $violation->user->last_name ?? null,
                                ])
                            )
                        );

                        if (
                            empty($apprehendingOfficer)
                        ) {
                            $apprehendingOfficer = 'N/A';
                        }
                    }
                }

                // ==================================================
                // RETURN EXCEL ROW
                // ALL DATA IS UPPERCASE
                // ==================================================

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
                    // Example:
                    // MENDOZA, REGINA BINUYA
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