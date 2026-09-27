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
        ]);

        // ==========================================================
        // FILTER BY STATUS
        // ==========================================================

        if ($this->status === 'Pending' || $this->status === 'Settled') {
            $query->where('status', $this->status);
        }

        // ==========================================================
        // SEARCH
        // Searches:
        // - Ticket Number
        // - Driver Name
        // - Driver License Number
        // - Vehicle Plate Number
        // ==========================================================

        if (!empty($this->search)) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {

                // Ticket Number
                $q->where(
                    'ticket_number',
                    'like',
                    "%{$search}%"
                )

                // Driver information
                ->orWhereHas('driver', function ($driver) use ($search) {

                    $driver->where(function ($nameQuery) use ($search) {

                        // Full name
                        $nameQuery
                            ->whereRaw(
                                "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                                ["%{$search}%"]
                            )

                            // First + Last name
                            ->orWhereRaw(
                                "CONCAT(first_name, ' ', last_name) LIKE ?",
                                ["%{$search}%"]
                            )

                            // First name
                            ->orWhere(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )

                            // Middle name
                            ->orWhere(
                                'middle_name',
                                'like',
                                "%{$search}%"
                            )

                            // Last name
                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            )

                            // License number
                            ->orWhere(
                                'license_number',
                                'like',
                                "%{$search}%"
                            );
                    });
                })

                // Vehicle Plate Number
                ->orWhereHas('vehicle', function ($vehicle) use ($search) {

                    $vehicle->where(
                        'plate_number',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        // ==========================================================
        // FILTER BY DATE FROM
        // ==========================================================

        if (!empty($this->dateFrom)) {
            $query->whereDate(
                'violation_date',
                '>=',
                $this->dateFrom
            );
        }

        // ==========================================================
        // FILTER BY DATE TO
        // ==========================================================

        if (!empty($this->dateTo)) {
            $query->whereDate(
                'violation_date',
                '<=',
                $this->dateTo
            );
        }

        // ==========================================================
        // GET FILTERED VIOLATIONS
        // ==========================================================

        return $query
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->get()
            ->map(function ($violation) {

                // ==================================================
                // GET VIOLATION NAMES
                // ==================================================

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

                // ==================================================
                // RETURN EXCEL ROW
                // ==================================================

                return [
                    // Ticket Information
                    $violation->ticket_number ?? 'N/A',
                    $violation->violation_date ?? 'N/A',
                    $violation->violation_time ?? 'N/A',

                    // Driver Information
                    $violation->driver->first_name ?? 'N/A',
                    $violation->driver->middle_name ?? 'N/A',
                    $violation->driver->last_name ?? 'N/A',
                    $violation->driver->license_number ?? 'N/A',
                    $violation->driver->address ?? 'N/A',
                    $violation->driver->contact_number ?? 'N/A',

                    // Vehicle Information
                    $violation->vehicle->plate_number ?? 'N/A',
                    $violation->vehicle->vehicle_type ?? 'N/A',
                    $violation->vehicle->owner_name ?? 'N/A',

                    // Violation Information
                    $violationNames,

                    // Location
                    $violation->location ?? 'N/A',

                    // Status
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

            'First Name',
            'Middle Name',
            'Last Name',
            'License Number',
            'Driver Address',
            'Contact Number',

            'Plate Number',
            'Vehicle Type',
            'Owner Name',

            'Violation(s)',
            'Location Address',

            'Status',
        ];
    }
}