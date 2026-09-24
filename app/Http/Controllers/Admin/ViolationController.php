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
            'violationTypes',
            'user',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'ticket_number',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhereHas('driver', function ($driverQuery) use ($search) {
                    $driverQuery
                        ->where(
                            'first_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'middle_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'last_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'license_number',
                            'like',
                            '%' . $search . '%'
                        );
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

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

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
            'violationTypes',
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
            'violationTypes',
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
            'violation_type_id' => [
                'required',
                'exists:violation_types,id',
            ],

            'additional_violation_type_ids' => [
                'nullable',
                'array',
            ],

            'additional_violation_type_ids.*' => [
                'required',
                'exists:violation_types,id',
            ],

            'status' => [
                'required',
                'in:Pending,Settled',
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'license_number' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'required',
                'string',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'license_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLLECT SELECTED VIOLATION TYPES
        |--------------------------------------------------------------------------
        */

        $violationTypeIds = [
            (int) $request->violation_type_id,
        ];

        if ($request->filled('additional_violation_type_ids')) {
            foreach ($request->additional_violation_type_ids as $additionalId) {
                $additionalId = (int) $additionalId;

                if (!in_array($additionalId, $violationTypeIds, true)) {
                    $violationTypeIds[] = $additionalId;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE MAIN VIOLATION RECORD
        |--------------------------------------------------------------------------
        */

        $violation->update([
            'violation_type_id' => $violationTypeIds[0],
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SYNCHRONIZE MULTIPLE VIOLATION TYPES
        |--------------------------------------------------------------------------
        |
        | This keeps the pivot table synchronized with the selections
        | made on the Edit Violation page.
        |
        */

        $violation->violationTypes()->sync($violationTypeIds);

        /*
        |--------------------------------------------------------------------------
        | UPDATE DRIVER INFORMATION
        |--------------------------------------------------------------------------
        */

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