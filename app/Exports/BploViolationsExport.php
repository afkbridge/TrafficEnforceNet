<?php

namespace App\Exports;

use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BploViolationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'user',
        ])
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->get()
            ->map(function ($violation) {

                // Use the newer multiple-violation relationship first.
                $violationNames = $violation->violationTypes
                    ->pluck('name')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                // Fall back to the old single violation relationship.
                if (empty($violationNames)) {
                    $violationNames =
                        $violation->violationType->name ?? 'N/A';
                }

                return [
                    $violation->ticket_number ?? 'N/A',

                    $violation->violation_date ?? 'N/A',

                    $violation->violation_time ?? 'N/A',

                    $violation->driver
                        ? $violation->driver->full_name
                        : 'N/A',

                    $violation->driver->license_number ?? 'N/A',

                    $violation->driver->license_type ?? 'N/A',

                    $violation->driver->license_expiration ?? 'N/A',

                    $violation->driver->address ?? 'N/A',

                    $violation->driver->contact_number ?? 'N/A',

                    $violation->vehicle->plate_number ?? 'N/A',

                    $violation->vehicle->vehicle_type ?? 'N/A',

                    $violation->vehicle->region_number ?? 'N/A',

                    $violation->vehicle->owner_name ?? 'N/A',

                    $violationNames,

                    $violation->location ?? 'N/A',

                    $violation->latitude ?? 'N/A',

                    $violation->longitude ?? 'N/A',

                    $violation->remarks ?? 'N/A',

                    $violation->user->name ?? 'N/A',

                    $violation->status ?? 'N/A',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Ticket Number',
            'Violation Date',
            'Violation Time',
            'Driver Name',
            'License Number',
            'License Type',
            'License Expiration',
            'Driver Address',
            'Contact Number',
            'Plate Number',
            'Vehicle Type',
            'Region Number',
            'Owner Name',
            'Violation(s)',
            'Location',
            'Latitude',
            'Longitude',
            'Remarks',
            'Enforcer',
            'Status',
        ];
    }
}