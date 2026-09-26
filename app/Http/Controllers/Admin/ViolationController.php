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
     * Display all violation records.
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

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Ticket Number
                |--------------------------------------------------------------------------
                */
                $q->where(
                    'ticket_number',
                    'like',
                    '%' . $search . '%'
                )

                /*
                |--------------------------------------------------------------------------
                | Driver
                |--------------------------------------------------------------------------
                */
                ->orWhereHas('driver', function ($driverQuery) use ($search) {

                    $driverQuery->where(function ($nameQuery) use ($search) {

                        // Full name including middle name
                        $nameQuery->whereRaw(
                            "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                            ['%' . $search . '%']
                        )

                        // First name + last name
                        ->orWhereRaw(
                            "CONCAT(first_name, ' ', last_name) LIKE ?",
                            ['%' . $search . '%']
                        )

                        // Individual name fields
                        ->orWhere(
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

                        // License number
                        ->orWhere(
                            'license_number',
                            'like',
                            '%' . $search . '%'
                        );
                    });
                })

                /*
                |--------------------------------------------------------------------------
                | Vehicle Plate
                |--------------------------------------------------------------------------
                */
                ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                    $vehicleQuery->where(
                        'plate_number',
                        'like',
                        '%' . $search . '%'
                    );
                })

                /*
                |--------------------------------------------------------------------------
                | Primary Violation Type
                |--------------------------------------------------------------------------
                */
                ->orWhereHas('violationType', function ($typeQuery) use ($search) {
                    $typeQuery->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );
                })

                /*
                |--------------------------------------------------------------------------
                | Additional Violation Types
                |--------------------------------------------------------------------------
                */
                ->orWhereHas('violationTypes', function ($typeQuery) use ($search) {
                    $typeQuery->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );
                })

                /*
                |--------------------------------------------------------------------------
                | Location
                |--------------------------------------------------------------------------
                */
                ->orWhere(
                    'location',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FROM
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_from')) {
            $query->whereDate(
                'violation_date',
                '>=',
                $request->date_from
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE TO
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_to')) {
            $query->whereDate(
                'violation_date',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GET RECORDS
        |--------------------------------------------------------------------------
        */
        $violations = $query
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.violations.index',
            compact('violations')
        );
    }

    /**
     * Display a driver's complete violation history.
     */
    public function driverHistory(Driver $driver)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD DRIVER INFORMATION
        |--------------------------------------------------------------------------
        */
        $driver->load([
            'vehicles',
            'violations.vehicle',
            'violations.violationType',
            'violations.violationTypes',
            'violations.user',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SORT VIOLATIONS
        |--------------------------------------------------------------------------
        */
        $violations = $driver->violations
            ->sortByDesc(function ($violation) {
                return ($violation->violation_date ?? '') . ' ' .
                    ($violation->violation_time ?? '');
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */
        $totalViolations = $violations->count();

        $pendingViolations = $violations
            ->filter(function ($violation) {
                return strtolower($violation->status ?? '') === 'pending';
            })
            ->count();

        $settledViolations = $violations
            ->filter(function ($violation) {
                return strtolower($violation->status ?? '') === 'settled';
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | DRIVER HISTORY PAGE
        |--------------------------------------------------------------------------
        */
        return view(
            'admin.violations.driver-history',
            compact(
                'driver',
                'violations',
                'totalViolations',
                'pendingViolations',
                'settledViolations'
            )
        );
    }

    /**
     * Show the form for creating a new violation.
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
     * Store a newly created violation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_number' => [
                'required',
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
                'nullable',
                'string',
                'max:255',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:255',
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

            'violation_type_id' => [
                'required',
                'exists:violation_types,id',
            ],

            'additional_violation_type_ids' => [
                'nullable',
                'array',
            ],

            'additional_violation_type_ids.*' => [
                'exists:violation_types,id',
            ],

            'violation_date' => [
                'required',
                'date',
            ],

            'violation_time' => [
                'required',
                'date_format:H:i',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
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
                'license_number' => $validated['license_number'],
            ],
            [
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'address' => $validated['address'] ?? null,
                'contact_number' => $validated['contact_number'] ?? null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE DRIVER INFORMATION IF EXISTING
        |--------------------------------------------------------------------------
        */
        $driver->first_name = $validated['first_name'];
        $driver->middle_name = $validated['middle_name'] ?? null;
        $driver->last_name = $validated['last_name'];
        $driver->address = $validated['address'] ?? $driver->address;
        $driver->contact_number = $validated['contact_number'] ?? $driver->contact_number;
        $driver->save();

        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        */
        $vehicle = Vehicle::firstOrCreate(
            [
                'plate_number' => $validated['plate_number'],
            ],
            [
                'driver_id' => $driver->id,
                'vehicle_type' => $validated['vehicle_type'] ?? null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE VEHICLE INFORMATION
        |--------------------------------------------------------------------------
        */
        $vehicle->driver_id = $driver->id;

        if (!empty($validated['vehicle_type'])) {
            $vehicle->vehicle_type = $validated['vehicle_type'];
        }

        $vehicle->save();

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
        | CREATE VIOLATION
        |--------------------------------------------------------------------------
        */
        $violation = Violation::create([
            'ticket_number' => $validated['ticket_number'],
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'violation_type_id' => $validated['violation_type_id'],
            'user_id' => Auth::id(),
            'violation_date' => $validated['violation_date'],
            'violation_time' => $validated['violation_time'],
            'location' => $validated['location'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'ticket_image' => $ticketImagePath,
            'status' => 'Pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ADDITIONAL VIOLATION TYPES
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['additional_violation_type_ids'])) {
            $violation->violationTypes()->sync(
                $validated['additional_violation_type_ids']
            );
        }

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
        AuditLogger::log(
            'CREATE_VIOLATION',
            'Created violation ticket ' .
                $violation->ticket_number . '.',
            $violation
        );

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
    public function show($id)
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
     * Show the form for editing a violation.
     */
    public function edit($id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'images',
        ])->findOrFail($id);

        $violationTypes = ViolationType::orderBy('name')->get();

        return view(
            'admin.violations.edit',
            compact(
                'violation',
                'violationTypes'
            )
        );
    }

    /**
     * Update an existing violation.
     */
    public function update(Request $request, $id)
    {
        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationTypes',
        ])->findOrFail($id);

        $validated = $request->validate([
            'violation_type_id' => [
                'required',
                'exists:violation_types,id',
            ],

            'additional_violation_type_ids' => [
                'nullable',
                'array',
            ],

            'additional_violation_type_ids.*' => [
                'exists:violation_types,id',
            ],

            'status' => [
                'required',
                'in:Pending,Settled',
            ],

            'remarks' => [
                'nullable',
                'string',
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
                'nullable',
                'string',
                'max:255',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:255',
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
        ]);

        /*
        |--------------------------------------------------------------------------
        | OLD VALUES FOR AUDIT
        |--------------------------------------------------------------------------
        */
        $oldStatus = $violation->status;

        /*
        |--------------------------------------------------------------------------
        | UPDATE DRIVER
        |--------------------------------------------------------------------------
        */
        $driver = $violation->driver;

        if ($driver) {
            $driver->first_name = $validated['first_name'];
            $driver->middle_name = $validated['middle_name'] ?? null;
            $driver->last_name = $validated['last_name'];
            $driver->license_number = $validated['license_number'];
            $driver->address = $validated['address'] ?? null;
            $driver->contact_number = $validated['contact_number'] ?? null;
            $driver->save();
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE VEHICLE
        |--------------------------------------------------------------------------
        */
        $vehicle = $violation->vehicle;

        if ($vehicle) {
            $vehicle->plate_number = $validated['plate_number'];
            $vehicle->vehicle_type = $validated['vehicle_type'] ?? null;
            $vehicle->save();
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE VIOLATION
        |--------------------------------------------------------------------------
        */
        $violation->violation_type_id =
            $validated['violation_type_id'];

        $violation->status =
            $validated['status'];

        $violation->remarks =
            $validated['remarks'] ?? null;

        $violation->save();

        /*
        |--------------------------------------------------------------------------
        | UPDATE ADDITIONAL VIOLATIONS
        |--------------------------------------------------------------------------
        */
        $additionalViolationIds =
            $validated['additional_violation_type_ids'] ?? [];

        $violation->violationTypes()->sync(
            $additionalViolationIds
        );

        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG
        |--------------------------------------------------------------------------
        */
        if ($oldStatus !== $violation->status) {

            AuditLogger::log(
                'STATUS_UPDATE',
                'Updated violation ticket ' .
                    $violation->ticket_number .
                    ' status from ' .
                    $oldStatus .
                    ' to ' .
                    $violation->status .
                    '.',
                $violation
            );

        } else {

            AuditLogger::log(
                'UPDATE_VIOLATION',
                'Updated violation ticket ' .
                    $violation->ticket_number .
                    '.',
                $violation
            );
        }

        return redirect()
            ->route('violations.show', $violation->id)
            ->with(
                'success',
                'Violation record updated successfully.'
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
            'traffic-violation-records.xlsx'
        );
    }
}