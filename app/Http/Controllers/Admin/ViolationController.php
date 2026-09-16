<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ViolationsExport;
use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\ViolationType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user',
        ]);

        // Search by ticket number, driver name,
        // license number, or vehicle plate number.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', '%' . $search . '%')
                    ->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery
                            ->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('middle_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('license_number', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where(
                            'plate_number',
                            'like',
                            '%' . $search . '%'
                        );
                    });
            });
        }

        // Filter by status.
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter from date.
        if ($request->filled('date_from')) {
            $query->whereDate(
                'violation_date',
                '>=',
                $request->date_from
            );
        }

        // Filter to date.
        if ($request->filled('date_to')) {
            $query->whereDate(
                'violation_date',
                '<=',
                $request->date_to
            );
        }

        // Get violation records with pagination.
        $violations = $query
            ->latest('violation_date')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.violations.index',
            compact('violations')
        );
    }

    public function show(string $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user',
            'images',
        ])->findOrFail($id);

        return view(
            'admin.violations.show',
            compact('violation')
        );
    }

    public function edit($id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user',
            'images',
        ])->findOrFail($id);

        $violationTypes = ViolationType::all();

        return view(
            'admin.violations.edit',
            compact(
                'violation',
                'violationTypes'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
        ])->findOrFail($id);

        $request->validate([
            // Violation validation
            'violation_type_id' => 'required',
            'status' => 'required',

            // Driver validation
            'first_name' => 'required',
            'last_name' => 'required',
            'license_number' => 'required',
            'address' => 'required',
        ]);

        // Update violation record.
        $violation->update([
            'violation_type_id' => $request->violation_type_id,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        // Update driver information.
        if ($violation->driver) {
            $violation->driver->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'license_number' => $request->license_number,
                'address' => $request->address,
                'contact_number' => $request->contact_number,
                'license_type' => $request->license_type,
            ]);
        }

        return redirect()
            ->route('violations.show', $id)
            ->with(
                'success',
                'Violation updated successfully.'
            );
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ViolationsExport(
                $request->search,
                $request->status,
                $request->date_from,
                $request->date_to
            ),
            'traffic_violation_records.xlsx'
        );
    }
}
