<?php

namespace App\Exports;

use App\Models\Violation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ViolationsExport implements FromCollection, WithHeadings
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $query = Violation::query()->with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'violationOtherTypes',
            'user',
        ]);

        // SEARCH: match the POSO Admin search fields.
        $search = trim((string) ($this->filters['search'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('driver', function (Builder $driver) use ($search) {
                        $driver->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('license_number', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function (Builder $vehicle) use ($search) {
                        $vehicle->where('plate_number', 'like', "%{$search}%");
                    });
            });
        }

        // STATUS
        $status = trim((string) ($this->filters['status'] ?? ''));

        if ($status !== '') {
            $query->where('status', $status);
        }

        // DATE RANGE
        $dateFrom = $this->filters['date_from'] ?? null;
        $dateTo = $this->filters['date_to'] ?? null;

        if ($dateFrom) {
            $query->whereDate('violation_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('violation_date', '<=', $dateTo);
        }

        return $query
            ->orderByDesc('violation_date')
            ->orderByDesc('violation_time')
            ->get()
            ->map(function ($violation) {
                $driver = $violation->driver;

                // SURNAME, FIRST NAME MIDDLE NAME
                $surname = trim((string) ($driver->last_name ?? ''));
                $firstName = trim((string) ($driver->first_name ?? ''));
                $middleName = trim((string) ($driver->middle_name ?? ''));

                $name = $surname !== '' ? $surname : '';

                if ($firstName !== '') {
                    $name .= ($name !== '' ? ', ' : '') . $firstName;
                }

                if ($middleName !== '') {
                    $name .= ($name !== '' ? ' ' : '') . $middleName;
                }

                if (trim($name) === '') {
                    $name = 'N/A';
                }

                // Collect primary and additional violations.
                $violationNames = [];

                if ($violation->violationType?->name) {
                    $violationNames[] = trim($violation->violationType->name);
                }

                if (!empty($violation->other_violation)) {
                    $violationNames[] = trim($violation->other_violation);
                }

                foreach ($violation->violationTypes as $type) {
                    if (!empty($type->name)) {
                        $violationNames[] = trim($type->name);
                    }
                }

                foreach ($violation->violationOtherTypes as $type) {
                    if (!empty($type->name)) {
                        $violationNames[] = trim($type->name);
                    }
                }

                $violationNames = array_values(array_unique(array_filter(
                    $violationNames,
                    fn ($value) => $value !== ''
                )));

                $violationText = !empty($violationNames)
                    ? implode(', ', $violationNames)
                    : 'N/A';

                // APPREHENDING OFFICER
                $officer = $violation->user?->name;

                if (empty($officer) && $violation->user) {
                    $officer = trim(implode(' ', array_filter([
                        $violation->user->first_name ?? null,
                        $violation->user->middle_name ?? null,
                        $violation->user->last_name ?? null,
                    ])));
                }

                $officer = $officer ?: 'N/A';

                // Keep the existing Excel column order and uppercase values.
                return [
                    strtoupper((string) ($violation->violation_date ?? 'N/A')),
                    strtoupper((string) ($violation->ticket_number ?? 'N/A')),
                    strtoupper((string) ($violation->vehicle?->vehicle_type ?? 'N/A')),
                    strtoupper((string) ($violation->vehicle?->plate_number ?? 'N/A')),
                    strtoupper((string) ($violation->location ?? 'N/A')),
                    strtoupper($violationText),
                    strtoupper($name),
                    strtoupper((string) ($driver?->address ?? 'N/A')),
                    strtoupper((string) ($driver?->license_number ?? 'N/A')),
                    strtoupper($officer),
                    strtoupper((string) ($violation->remarks ?? 'N/A')),
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
            'LICENSE NO',
            'Apprehending Officer',
            'Remarks',
        ];
    }
}
