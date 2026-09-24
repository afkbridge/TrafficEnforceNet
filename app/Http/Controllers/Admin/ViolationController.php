<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ViolationsExport;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Violation;
use App\Models\ViolationImage;
use App\Models\ViolationType;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ViolationController extends Controller
{
    /**
     * Display violation records.
     */
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

    /**
     * Show the Add Citation page.
     */
    public function create()
    {
        $violationTypes = ViolationType::orderBy('name')->get();

        return view(
            'admin.violations.create',
            compact('violationTypes')
        );
    }

    /**
     * Store an admin-encoded citation record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:violations,ticket_number',
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'middle_name' => [
                'nullable',
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

            'birth_date' => [
                'nullable',
                'date',
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

            'license_expiration' => [
                'nullable',
                'date',
            ],

            'plate_number' => [
                'required',
                'string',
                'max:255',
            ],

            'vehicle_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'region_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'owner_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'violation_type_id' => [
                'required',
                'exists:violation_types,id',
            ],

            'additional_violation_type_ids' => [
                'nullable',
                'array',
            ],

            'additional_violation_type_ids.*' => [
                'nullable',
                'exists:violation_types,id',
            ],

            'other_violation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'additional_other_violation_names' => [
                'nullable',
                'array',
            ],

            'additional_other_violation_names.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'violation_date' => [
                'required',
                'date',
            ],

            'violation_time' => [
                'required',
                'date_format:H:i',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'ticket_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],

            'evidence_images' => [
                'nullable',
                'array',
            ],

            'evidence_images.*' => [
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        */

        $driver = Driver::firstOrCreate(
            [
                'license_number' => $request->license_number,
            ],
            [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'contact_number' => $request->contact_number,
                'license_type' => $request->license_type,
                'license_expiration' => $request->license_expiration,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE DRIVER IF EXISTING
        |--------------------------------------------------------------------------
        */

        if (!$driver->wasRecentlyCreated) {
            $driver->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'contact_number' => $request->contact_number,
                'license_type' => $request->license_type,
                'license_expiration' => $request->license_expiration,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        */

        $plateNumber = strtoupper(trim($request->plate_number));

        $vehicle = Vehicle::firstOrCreate(
            [
                'plate_number' => $plateNumber,
            ],
            [
                'driver_id' => $driver->id,
                'vehicle_type' => $request->vehicle_type,
                'region_number' => $request->region_number,
                'owner_name' => $request->owner_name,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE VEHICLE IF EXISTING
        |--------------------------------------------------------------------------
        */

        if (!$vehicle->wasRecentlyCreated) {
            $vehicle->update([
                'driver_id' => $driver->id,
                'vehicle_type' => $request->vehicle_type,
                'region_number' => $request->region_number,
                'owner_name' => $request->owner_name,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | COLLECT VIOLATION TYPES
        |--------------------------------------------------------------------------
        */

        $violationTypeIds = [
            (int) $request->violation_type_id,
        ];

        /*
        |--------------------------------------------------------------------------
        | ADDITIONAL VIOLATIONS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('additional_violation_type_ids')) {
            foreach ($request->additional_violation_type_ids as $additionalId) {
                $additionalId = (int) $additionalId;

                if (
                    $additionalId > 0 &&
                    !in_array($additionalId, $violationTypeIds, true)
                ) {
                    $violationTypeIds[] = $additionalId;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | OTHER PRIMARY VIOLATION
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('other_violation') &&
            strtolower(trim($request->other_violation)) !== 'other'
        ) {
            $otherViolation = trim($request->other_violation);

            $otherType = ViolationType::firstOrCreate(
                [
                    'name' => $otherViolation,
                ],
                [
                    'description' => 'Added by administrator during citation',
                ]
            );

            if (!in_array($otherType->id, $violationTypeIds, true)) {
                $violationTypeIds[] = $otherType->id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | OTHER ADDITIONAL VIOLATIONS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('additional_other_violation_names')) {
            foreach (
                $request->additional_other_violation_names
                as $additionalOtherViolation
            ) {
                $additionalOtherViolation = trim($additionalOtherViolation);

                if ($additionalOtherViolation === '') {
                    continue;
                }

                $otherType = ViolationType::firstOrCreate(
                    [
                        'name' => $additionalOtherViolation,
                    ],
                    [
                        'description' => 'Added by administrator during citation',
                    ]
                );

                if (!in_array($otherType->id, $violationTypeIds, true)) {
                    $violationTypeIds[] = $otherType->id;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TICKET IMAGE
        |--------------------------------------------------------------------------
        */

        $ticketImagePath = null;

        if ($request->hasFile('ticket_image')) {
            $ticketImagePath = $request
                ->file('ticket_image')
                ->store('violations/tickets', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | TICKET NUMBER
        |--------------------------------------------------------------------------
        */

        $ticketNumber = $request->filled('ticket_number')
            ? trim($request->ticket_number)
            : 'TN-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

        /*
        |--------------------------------------------------------------------------
        | CREATE VIOLATION
        |--------------------------------------------------------------------------
        */

        $violation = Violation::create([
            'ticket_number' => $ticketNumber,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'violation_type_id' => $violationTypeIds[0],
            'user_id' => Auth::id(),
            'violation_date' => $request->violation_date,
            'violation_time' => $request->violation_time,
            'location' => $request->location,
            'remarks' => $request->remarks,
            'ticket_image' => $ticketImagePath,
            'status' => 'Pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SYNC MULTIPLE VIOLATIONS
        |--------------------------------------------------------------------------
        */

        $violation->violationTypes()->sync($violationTypeIds);

        /*
        |--------------------------------------------------------------------------
        | EVIDENCE IMAGES
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('evidence_images')) {
            foreach ($request->file('evidence_images') as $image) {
                $imagePath = $image->store(
                    'violations/evidence',
                    'public'
                );

                ViolationImage::create([
                    'violation_id' => $violation->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG
        |--------------------------------------------------------------------------
        */

        try {
            AuditLogger::log(
                'CREATE',
                'Violation',
                $violation->id,
                'Administrator encoded traffic citation ' . $violation->ticket_number
            );
        } catch (\Throwable $e) {
            // Do not prevent the citation from being saved
            // if audit logging encounters an error.
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('violations.show', $violation->id)
            ->with(
                'success',
                'Citation record added successfully.'
            );
    }

    /**
     * Display a specific violation.
     */
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

    /**
     * Show the edit violation page.
     */
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

    /**
     * Update a violation.
     */
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

    /**
     * Export violation records to Excel.
     */
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
